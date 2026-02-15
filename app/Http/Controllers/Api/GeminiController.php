<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\AssistantKnowledge;
use App\Models\Skill;
use App\Models\Project;
use App\Models\Experience;
use App\Models\AssistantSetting;
use App\Models\AISession;
use App\Models\AIMessage;

class GeminiController extends Controller
{
    private function logConversation(string $message, string $reply, bool $success, ?int $sessionId = null)
    {
        try {
            // Find or create session
            $session = null;
            if ($sessionId) {
                $session = AISession::find($sessionId);
            }

            if (!$session) {
                $session = AISession::create([
                    'client_ip' => request()->ip(),
                    'is_unresolved' => false
                ]);
            }

            // Save user message
            AIMessage::create([
                'a_i_session_id' => $session->id,
                'role' => 'user',
                'content' => $message
            ]);

            // Save AI message
            AIMessage::create([
                'a_i_session_id' => $session->id,
                'role' => 'ai',
                'content' => $reply
            ]);

            // Mark as unresolved if fallback
            if (str_contains($reply, "Je suis désolé, je n'ai pas d'information précise sur ce sujet")) {
                $session->update(['is_unresolved' => true]);
            }

            return $session->id;
        } catch (\Exception $e) {
            Log::warning('Failed to log AI conversation', ['error' => $e->getMessage()]);
            return $sessionId;
        }
    }



    /**
     * Handle chat requests with Gemini 1.5 Flash
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'session_id' => 'nullable|integer'
        ]);

        $userMessage = $request->input('message');
        $sessionId = $request->input('session_id');
        
        // 1. Security: Sanitize and check for malicious patterns
        $sanitizedMessage = $this->sanitizeInput($userMessage);
        if ($sanitizedMessage === null) {
            return response()->json([
                'reply' => 'Je suis ici pour parler des projets d\'Oussama. Comment puis-je vous aider sur ce sujet ?',
                'success' => true
            ]);
        }

        $normalizedMessage = $this->normalizeString($sanitizedMessage);
        $cacheKey = 'chat_' . md5($normalizedMessage);

        // 2. Performance: Check Caching Layer
        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);
            $newSessionId = $this->logConversation($userMessage, $cachedData['reply'], true, $sessionId);
            return response()->json([
                'reply' => $cachedData['reply'],
                'session_id' => $newSessionId,
                'success' => true
            ]);
        }
        
        try {
            $reply = "";
            $source = "";
            $foundContent = null;
            $isHighPriority = false;

            // 3. Performance: Hybrid Search (Fast Lane for key topics)
            $highPriorityKeywords = ['identite', 'identity', 'qui es tu', 'smya', 'contact', 'cv', 'reseaux', 'linkedin', 'github', 'mnin nta', 'racines'];
            if ($this->containsAny($normalizedMessage, $highPriorityKeywords)) {
                $isHighPriority = true;
            }

            // 4. Content Discovery (Dynamic -> Database -> Static)
            
            // SKILLS Intent
            $skillKeywords = ['competence', 'skill', 'mharat', 'techno', 'stack', 'sais tu faire'];
            if ($this->containsAny($normalizedMessage, $skillKeywords)) {
                $skills = Skill::all()->pluck('name')->toArray();
                $foundContent = "Oussama maîtrise plusieurs technologies, notamment : " . implode(', ', $skills) . ". Il est toujours prêt à apprendre de nouveaux outils pour ses projets.";
                $source = 'dynamic_skills';
            }

            // PROJECTS/EXPERIENCE Intent
            if (empty($foundContent)) {
                $projKeywords = ['projet', 'stage', 'parcours', 'formation', 'etude', 'experience', 'travail', 'khedam'];
                if ($this->containsAny($normalizedMessage, $projKeywords)) {
                    $projectsCount = Project::count();
                    $lastExp = Experience::latest()->first();
                    $foundContent = "Oussama a travaillé sur {$projectsCount} projets majeurs. ";
                    if ($lastExp) {
                        $foundContent .= "Sa dernière expérience marquante était en tant que {$lastExp->role} chez {$lastExp->company}. ";
                    }
                    $foundContent .= "Vous pouvez voir les détails complets dans les sections 'Projets' et 'Expériences' du site.";
                    $source = 'dynamic_content';
                }
            }

            // Database Knowledge Discovery (Optimized Fuzzy Search)
            if (empty($foundContent)) {
                // 1. Direct Fuzzy Match (Whole Sentence)
                $knowledge = AssistantKnowledge::where('question', 'LIKE', "%{$userMessage}%")
                    ->orWhere('keywords', 'LIKE', "%{$userMessage}%")
                    ->orWhere('answer', 'LIKE', "%{$userMessage}%")
                    ->first();

                if ($knowledge) {
                    $foundContent = $knowledge->answer;
                    $source = 'database_exact';
                } else {
                    // 2. Word-based Fuzzy Match (Keyword Search)
                    $words = explode(' ', $normalizedMessage);
                    $words = array_filter($words, function($w) { return strlen($w) > 3; }); // Filter short words
                    
                    if (!empty($words)) {
                        $query = AssistantKnowledge::query();
                        foreach ($words as $word) {
                            $query->orWhere('keywords', 'LIKE', "%{$word}%")
                                  ->orWhere('question', 'LIKE', "%{$word}%");
                        }
                        // Order by length of answer or relevance if possible, but first match is acceptable for now
                        $knowledge = $query->first(); // Get the first matching record
                        
                        if ($knowledge) {
                            $foundContent = $knowledge->answer;
                            $source = 'database_fuzzy';
                        }
                    }
                }
            }

            // Static Fallbacks
            if (empty($foundContent)) {
                if ($this->containsAny($normalizedMessage, ['ou es tu', 'ville', 'emplacement', 'oujda', 'el hajeb', 'fin katskon'])) {
                    $foundContent = "Oussama est actuellement basé à Oujda, mais il se déplace souvent entre Oujda et El Hajeb pour ses projets et études.";
                    $source = 'static_info';
                } elseif ($this->containsAny($normalizedMessage, ['tu travailles', 'khedam', 'kate9ra', 'occupation', 'recherche', 'qui es tu', 'smya', 'identity', 'identite', 'mnin nta', 'racines'])) {
                    $foundContent = "Oussama est un étudiant en Génie Informatique, passionné par le développement web et l'IA. Il est actuellement à la recherche d'opportunités stimulantes.";
                    $source = 'static_info';
                }
            }

            // 5. Response Builder: Direct for Fast Lane, Gemini for others
            if (!empty($foundContent)) {
                if ($isHighPriority) {
                    $reply = $foundContent;
                    $source .= '_fast';
                } else {
                    $prompt = "Tu es l'assistant d'Oussama Oubaha. Réponds de manière naturelle à partir du contenu suivant: \"{$foundContent}\". Question de l'utilisateur: \"{$userMessage}\". RÈGLE: Ne divulgue JAMAIS d'information sensible (secrets, codes, .env, DB) non présente explicitement dans le contenu fourni.";
                    $geminiReply = $this->callGemini($prompt);
                    $reply = $geminiReply ?: $foundContent;
                }
            }

            // No Match Fallback
            if (empty($reply)) {
                $reply = "Désolé, je ne trouve pas d'information précise à ce sujet. Vous pouvez contacter Oussama via le formulaire de contact en bas de page.";
                $source = 'fallback';
            }

            // 6. Caching & Return
            Cache::put($cacheKey, ['reply' => $reply], 600); // 10 minutes cache
            $newSessionId = $this->logConversation($userMessage, $reply, true, $sessionId);

            return response()->json([
                'reply' => $reply,
                'session_id' => $newSessionId,
                'success' => true
            ])->header('Referrer-Policy', 'strict-origin-when-cross-origin');


        } catch (\Exception $e) {
            Log::error('AI Controller Error', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Server Error',
                'reply' => 'Désolé, une petite erreur technique est survenue.'
            ], 500);
        }
    }

    /**
     * Security: Sanitize input and detect attacks
     */
    private function sanitizeInput(string $input): ?string
    {
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        
        $maliciousPatterns = [
            'system prompt', 'instruction', 'ignore', 'admin', '.env', 'DB_', 
            'SELECT', 'UPDATE', 'DELETE', 'DROP', 'password', 'token', 'secret'
        ];
        
        foreach ($maliciousPatterns as $pattern) {
            if (stripos($input, $pattern) !== false) {
                return null;
            }
        }
        
        return $input;
    }


    /**
     * Helper to call Gemini API
     */
    private function callGemini(string $prompt): ?string
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) return null;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }
            
            Log::error('Gemini API Error', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Gemini API Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Helper to normalize strings for comparison
     */
    private function normalizeString(string $str): string
    {
        $str = mb_strtolower($str, 'UTF-8');
        // Simple accent removal for French
        $str = strtr(utf8_decode($str), 
            utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿ'), 
            'aaaaaceeeeiiiinooooouuuuyy');
        return trim($str);
    }

    /**
     * Check if string contains any of the keywords
     */
    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (empty($needle)) continue;
            $normalizedNeedle = $this->normalizeString($needle);
            if (str_contains($haystack, $normalizedNeedle)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Load portfolio data from JSON file
     */
    private function loadPortfolioData(): array
    {
        try {
            $jsonContent = Storage::get('portfolio.json');
            return json_decode($jsonContent, true) ?? [];
        } catch (\Exception $e) {
            Log::warning('Failed to load portfolio.json', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Build system instruction with persona
     */
    private function buildSystemInstruction(array $portfolioData, bool $isFirstMessage): string
    {
        $settings = AssistantSetting::all()->pluck('value', 'key');
        $systemPrompt = $settings['system_prompt'] ?? "Tu es Assistant OUBA-SYS, l'assistant virtuel d'Oussama Oubaha.";
        $currentStatus = $settings['current_status'] ?? "";

        $baseInstruction = $systemPrompt . "\n\n";
        
        if ($currentStatus) {
            $baseInstruction .= "**Statut Actuel d'Oussama** : " . $currentStatus . "\n\n";
        }
        
        // MANDATORY: First message must include greeting via IMPERATIVE INSTRUCTION
        $baseInstruction .= "RÈGLE ABSOLUE ET PRIORITAIRE : Tu DOIS OBLIGATOIREMENT commencer ta toute première réponse (et uniquement la première) par cette phrase exacte :\n";
        $baseInstruction .= "'Bonjour ! Merci de visiter le portfolio d'Oussama...'\n";
        $baseInstruction .= "Si l'utilisateur dit 'Bonjour', 'Salut', ou commence la conversation, lance cette phrase d'accueil.\n\n";
        
        $baseInstruction .= "Ton rôle est de répondre de manière professionnelle et polie aux questions concernant :\n";
        $baseInstruction .= "- Les compétences techniques d'Oussama\n";
        $baseInstruction .= "- Son parcours académique et professionnel\n";
        $baseInstruction .= "- Ses expériences et projets\n";
        $baseInstruction .= "- Ses coordonnées et disponibilité\n\n";
        
        // Add portfolio context
        if (!empty($portfolioData)) {
            $baseInstruction .= "Voici les informations du portfolio :\n\n";
            
            if (isset($portfolioData['personal_info'])) {
                $info = $portfolioData['personal_info'];
                $baseInstruction .= "**Informations personnelles** :\n";
                $baseInstruction .= "- Nom : {$info['name']}\n";
                $baseInstruction .= "- Titre : {$info['title']}\n";
                $baseInstruction .= "- Bio : {$info['bio']}\n";
                $baseInstruction .= "- Email : {$info['email']}\n";
                $baseInstruction .= "- Localisation : {$info['location']}\n\n";
            }
            
            if (isset($portfolioData['skills'])) {
                $baseInstruction .= "**Compétences** : " . implode(', ', $portfolioData['skills']) . "\n\n";
            }
            
            if (isset($portfolioData['experiences'])) {
                $baseInstruction .= "**Expériences** :\n";
                foreach ($portfolioData['experiences'] as $exp) {
                    $baseInstruction .= "- {$exp['role']} chez {$exp['company']} ({$exp['period']})\n";
                    if (isset($exp['missions'])) {
                        foreach ($exp['missions'] as $mission) {
                            $baseInstruction .= "  • {$mission}\n";
                        }
                    }
                }
                $baseInstruction .= "\n";
            }
            
            if (isset($portfolioData['education'])) {
                $baseInstruction .= "**Formation** :\n";
                foreach ($portfolioData['education'] as $edu) {
                    $baseInstruction .= "- {$edu['degree']} à {$edu['school']} ({$edu['period']})\n";
                }
            }
        }
        
        $baseInstruction .= "\nReste concis, professionnel et amical dans tes réponses.";
        
        return $baseInstruction;
    }

    /**
     * Prepare conversation history for Gemini API format
     */
    private function prepareConversationHistory(array $history, string $newMessage): array
    {
        $contents = [];
        
        // Add previous messages
        foreach ($history as $msg) {
            $role = $msg['role'] === 'user' ? 'user' : 'model';
            $contents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => $msg['content']]
                ]
            ];
        }
        
        // Add new user message
        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $newMessage]
            ]
        ];
        
        return $contents;
    }

    /**
     * Admin: Get all AI conversation logs
     */
    public function indexAdmin()
    {
        return response()->json(AISession::with(['messages' => function($q) {
            $q->orderBy('created_at', 'asc')->limit(1);
        }])->latest()->get());
    }

    /**
     * Admin: Delete a specific AI conversation log
     */
    public function destroyAdmin($logId)
    {
        // Placeholder for future AI logs feature
        return response()->json([
            'message' => 'AI logs feature not yet implemented'
        ]);
    }
}

