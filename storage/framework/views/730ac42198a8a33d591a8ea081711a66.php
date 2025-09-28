<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Skincare Analysis Submission</title>
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
            background: #1f2937;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .info-section {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }
        .analysis-section {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 4px solid #10b981;
        }

        .concern-item {
            background: #f3f4f6;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 6px;
            border-left: 3px solid #14b8a6;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .timestamp {
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔬 New Skincare Analysis Submission</h1>
        <p class="timestamp"><?php echo e(now()->format('F j, Y \a\t g:i A')); ?></p>
    </div>

    <div class="content">
        <div class="info-section">
            <h3>👤 Customer Information</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; width: 30%;">Name:</td>
                    <td style="padding: 8px 0;"><?php echo e($submission->name); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Email:</td>
                    <td style="padding: 8px 0;"><?php echo e($submission->email); ?></td>
                </tr>
                <?php if($submission->phone): ?>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Phone:</td>
                    <td style="padding: 8px 0;"><?php echo e($submission->phone); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Skin Type:</td>
                    <td style="padding: 8px 0;"><?php echo e(ucfirst($submission->skin_type)); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Submission ID:</td>
                    <td style="padding: 8px 0; font-family: monospace;"><?php echo e($submission->uuid); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Submitted:</td>
                    <td style="padding: 8px 0;"><?php echo e($submission->created_at->format('F j, Y \a\t g:i A')); ?></td>
                </tr>
            </table>

            <?php if($submission->pinterest_url): ?>
            <div style="margin-top: 15px;">
                <strong>Pinterest Reference:</strong><br>
                <a href="<?php echo e($submission->pinterest_url); ?>" target="_blank" style="color: #3b82f6;"><?php echo e($submission->pinterest_url); ?></a>
            </div>
            <?php endif; ?>
        </div>

        <div class="analysis-section">
            <h3>🎯 Analysis Results</h3>

            <?php if($submission->scanResults && $submission->scanResults->count() > 0): ?>
                <h4>🎯 Identified Skin Concerns:</h4>
                <?php $__currentLoopData = $submission->scanResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="concern-item">
                        <strong><?php echo e(ucfirst(str_replace('_', ' ', $result->concern))); ?></strong>
                        <span style="float: right; color: #14b8a6;"><?php echo e($result->score); ?> / 5</span>
                        <div style="clear: both; margin-top: 8px; font-size: 14px; color: #6b7280; line-height: 1.4;">
                            <?php echo e($result->explanation); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

        <?php if($submission->uploaded_image): ?>
        <div class="info-section">
            <h3>📸 Uploaded Images</h3>
            <p><strong>Primary Image:</strong> <?php echo e($submission->uploaded_image); ?></p>
            <?php if($submission->additional_image): ?>
                <p><strong>Additional Image:</strong> <?php echo e($submission->additional_image); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div style="background: #fef3c7; padding: 15px; border-radius: 6px; border: 1px solid #f59e0b; margin: 20px 0;">
            <strong>💡 Next Steps:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Customer has been automatically emailed their personalized results</li>
                <li>Product recommendations are available in their dashboard</li>
                <li>Follow up may be required for complex cases</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>This is an automated notification from SkinTel Scanner.</p>
        <p>© <?php echo e(date('Y')); ?> SkinTel Scanner Internal System</p>
    </div>
</body>
</html>
<?php /**PATH /Users/orderonlinemac5/Labs/upwork/Schyler/skintel-scanner/resources/views/emails/internal-analysis-notification.blade.php ENDPATH**/ ?>