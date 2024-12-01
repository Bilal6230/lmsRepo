<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Report</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f7f9fc; color: #333; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center;">

    <div style="width: 90%; max-width: 600px; padding: 40px 30px; background-color: #fff; border-radius: 10px;">
        <!-- Report Title -->
        <div style="font-size: 28px; font-weight: 700; color: #1a1a1a; margin-bottom: 50px; text-align: center;">Feedback Report</div>

        <!-- Student Section -->
        <table style="width: 100%; margin-bottom: 30px;">
            <tr>
                <td style="font-size: 16px; font-weight: bold; color: #555;">Student Info:</td>
            </tr>
            <tr>
                <td style="font-size: 16px; width: 40%; font-weight: bold; color: #555;">Name:</td>
                <td style="font-size: 18px; color: #333; padding-left: 10px; padding:5px; border-left: 2px solid #0066cc;"><?php echo e($request->studentName ?? ''); ?></td>
            </tr>
            <tr>
                <td style="font-size: 16px; font-weight: bold; color: #555;">Marks:</td>
                <td style="font-size: 18px; color: #333; padding-left: 10px; padding:5px; border-left: 2px solid #0066cc;"><?php echo e($request->studentMark ?? ''); ?></td>
            </tr>
            <tr>
                <td style="font-size: 16px; font-weight: bold; color: #555;">Grade:</td>
                <td style="font-size: 18px; color: #333; padding-left: 10px; padding:5px; border-left: 2px solid #0066cc;"><?php echo e($request->studentGrade ?? ''); ?></td>
            </tr>
            <tr>
                <td style="font-size: 16px; font-weight: bold; color: #555;">Comment:</td>
                <td style="font-size: 18px; color: #333; padding-left: 10px; padding:5px; border-left: 2px solid #0066cc;"><?php echo e($request->studentComent ?? ''); ?></td>
            </tr>
        </table>
        <hr style="margin-bottom: 10px;">

        <!-- Grades Section -->
        <table style="width: 100%; margin-bottom: 30px; ">
            <tr>
                <td style="font-size: 16px;width: 40%; font-weight: bold; color: #555;">Percentage Grade:</td>
                <td style="font-size: 18px; color: #333; padding-left: 10px; padding:5px; border-left: 2px solid #0066cc;"><?php echo e($request->grade ?? ''); ?></td>
            </tr>
            <tr>
                <td style="font-size: 16px; font-weight: bold; color: #555;">Letter Grade:</td>
                <td style="font-size: 18px; color: #333; padding-left: 10px; padding:5px; border-left: 2px solid #0066cc;">[Your Letter Grade Here]</td>
            </tr>
            <tr>
                <td style="font-size: 16px; font-weight: bold; color: #555;">IB Grade:</td>
                <td style="font-size: 18px; color: #333; padding-left: 10px; padding:5px; border-left: 2px solid #0066cc;">[Your IB Grade Here]</td>
            </tr>
            <tr>
                <td style="font-size: 16px; font-weight: bold; color: #555;">A.I Reliability Index:</td>
                <td style="font-size: 18px; color: #333; padding-left: 10px; padding:5px; border-left: 2px solid #0066cc;"><?php echo e($request->reliabilityIndex ?? ''); ?></td>
            </tr>
        </table>
        <hr style="margin-bottom: 10px;">
        <!-- Feedback Section -->
        <table style="width: 100%; margin-bottom: 30px;">
            <tr>
                <td style="font-size: 16px; font-weight: bold; color: #555;">Feedback:</td>
             </tr> 
            <tr>
                 <td style="font-size: 18px; color: #333; padding-left: 10px; padding:5px; border-left: 2px solid #0066cc;"><?php echo e($request->feedback ?? ''); ?></td>
            </tr>
        </table>

        <!-- Assignment Copy Section -->
        <table style="width: 100%; margin-top: 30px;">
            <tr>
                <td style="font-size: 16px; font-weight: bold; color: #555;">Assignment Copy:</td>
            </tr>
            <tr>
                <td style="padding-top: 10px;"><?php echo e($request->assignmentCntent ?? ''); ?></td>
            </tr>
        </table>
    </div>

</body>

</html>
<?php /**PATH /home/moeez/Documents/lms-skoleai-main/resources/views/admin/template/template.blade.php ENDPATH**/ ?>