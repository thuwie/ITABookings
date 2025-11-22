<!-- email_approval_template.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Phê duyệt đơn đăng ký</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f4f4f4; color:#333;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color:#004aad; color:#ffffff; padding:20px; text-align:center; font-size:24px; font-weight:bold;">
                            ITABookings
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding:30px;">
                            <h2 style="color:#004aad;">Xin chào <?= htmlspecialchars($userName) ?>,</h2>
                            <p style="line-height:1.6; font-size:16px;">
                                Sau quá trình xem xét và đánh giá, chúng tôi thấy doanh nghiệp của bạn đủ tiêu chí để trở thành một nhà cung cấp trên hệ thống ITABookings.
                            </p>
                            <p style="line-height:1.6; font-size:16px;">
                                Tài khoản của bạn đã được <strong>duyệt</strong>.
                            </p>
                            <p style="line-height:1.6; font-size:16px;">
                                <strong>Ngày duyệt:</strong> <?= htmlspecialchars($formatted) ?>
                            </p>
                            <a href="https://46e1609e70e8.ngrok-free.app/provider/<?= htmlspecialchars($userId) ?>/extra-costs" style="line-height:1.6; font-size:16px;">
                                Bạn cần xem qua một vài thông tin cũng như quy định về lợi nhuận khi trở thành nhà cung cấp tại hệ thống ITABookings.
                            </a>
                            <p style="line-height:1.6; font-size:14px; color:#777;">
                                Vui lòng không phản hồi email này.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f0f0f0; padding:20px; text-align:center; font-size:12px; color:#777;">
                            &copy; <?= date('Y') ?> ITABookings. Mọi quyền được bảo lưu.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
