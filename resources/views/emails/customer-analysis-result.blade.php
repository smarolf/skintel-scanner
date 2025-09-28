<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Skincare Analysis Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #14b8a6, #10b981);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px 20px;
            border-radius: 0 0 8px 8px;
        }
        .analysis-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 4px solid #14b8a6;
        }
        .cta-button {
            display: inline-block;
            background: #14b8a6;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .concern-item {
            background: #f3f4f6;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 6px;
            border-left: 3px solid #14b8a6;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌟 Your Skincare Analysis Results</h1>
        <p>Personalized skincare recommendations just for you!</p>
    </div>

    <div class="content">
        <p>Hello <strong>{{ $submission->name }}</strong>,</p>

        <p>Thank you for using SkinTel Scanner! We've completed your personalized skincare analysis and have exciting recommendations waiting for you.</p>

        <div class="analysis-section">
            <h3>📊 Your Skin Analysis Summary</h3>
            
            @if($submission->scanResults && $submission->scanResults->count() > 0)
                <h4>🎯 Identified Skin Concerns:</h4>
                @foreach($submission->scanResults as $result)
                    <div class="concern-item">
                        <strong>{{ ucfirst(str_replace('_', ' ', $result->concern_type)) }}</strong>
                        @if($result->confidence_score)
                            <span style="float: right; color: #14b8a6;">{{ number_format($result->confidence_score * 100, 1) }}% confidence</span>
                        @endif
                        <div style="clear: both; margin-top: 8px; font-size: 14px; color: #6b7280; line-height: 1.4;">
                            @switch($result->concern_type)
                                @case('redness')
                                    Redness can be caused by inflammation, sensitivity, or conditions like rosacea. Our recommended products contain soothing ingredients like niacinamide and centella asiatica to calm irritated skin and reduce visible redness.
                                    @break
                                @case('texture')
                                    Uneven skin texture often results from dead skin cell buildup or enlarged pores. We've selected gentle exfoliating products with AHA/BHA and retinoids to smooth your skin's surface and promote cell turnover.
                                    @break
                                @case('oiliness')
                                    Excess oil production can lead to shine and clogged pores. Our curated products include oil-controlling ingredients like salicylic acid and niacinamide to balance sebum production without over-drying your skin.
                                    @break
                                @case('dryness')
                                    Dry skin lacks moisture and can feel tight or flaky. Your recommended routine includes hydrating ingredients like hyaluronic acid, ceramides, and glycerin to restore and maintain optimal moisture levels.
                                    @break
                                @case('poreVisibility')
                                    Visible pores are often caused by oil buildup and loss of skin elasticity. We've chosen products with pore-minimizing ingredients like niacinamide and retinoids to help tighten and refine your skin's appearance.
                                    @break
                                @case('darkCircles')
                                    Dark circles can result from genetics, lack of sleep, or thin under-eye skin. Our selected eye care products contain caffeine, vitamin C, and peptides to brighten and strengthen the delicate eye area.
                                    @break
                                @case('acneScarring')
                                    Acne scarring occurs when breakouts damage the skin's deeper layers. Your routine includes ingredients like vitamin C, retinoids, and alpha hydroxy acids to promote healing and fade post-inflammatory hyperpigmentation.
                                    @break
                                @default
                                    This skin concern has been identified through our advanced analysis. Your personalized routine includes targeted ingredients to address this specific issue effectively.
                            @endswitch
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="analysis-section">
            <h3>💡 What's Next?</h3>
            <p>Based on your unique skin profile, we've curated a personalized product routine that targets your specific concerns.</p>

            <p>Your personalized recommendations include:</p>
            <ul>
                <li>✨ Targeted treatments for your skin concerns</li>
                <li>🧴 Products specifically chosen for your skin</li>
                <li>📋 Step-by-step routine guidance</li>
                <li>🛒 Easy shopping experience</li>
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="{{ $recommendationsUrl }}" class="cta-button">
                🔍 View Your Personalized Recommendations
            </a>
        </div>

        <div class="analysis-section">
            <h3>💎 Why These Recommendations?</h3>
            <p>Our AI-powered analysis has identified the most effective ingredients and formulations for your specific skin profile. Each recommended product has been selected to work synergistically with your skin type and address your primary concerns.</p>
        </div>

        <p>If you have any questions about your results or need assistance with your skincare routine, please don't hesitate to reach out to our team.</p>

        <p>Here's to healthier, more radiant skin!</p>

        <p>Best regards,<br>
        <strong>The SkinTel Scanner Team</strong></p>
    </div>

    <div class="footer">
        <p>This email was sent because you completed a skin analysis on SkinTel Scanner.</p>
        <p>© {{ date('Y') }} SkinTel Scanner. All rights reserved.</p>
    </div>
</body>
</html>
