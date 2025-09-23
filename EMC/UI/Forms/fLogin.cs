using EMC.UI.Controls;
using EMC.UI.Helpers;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Drawing.Drawing2D;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using static System.Windows.Forms.AxHost;

namespace EMC.UI.Forms
{
    public partial class fLogin : Form
    {
        // Lưu vị trí và kích thước gốc của btnLogin để khôi phục
        private Point originalBtnLoginLocation;
        private Size originalBtnLoginSize;
        private List<PlaceholderTextBox> otpBoxes = new List<PlaceholderTextBox>();  // Thêm danh sách để lưu otpBox

        public fLogin()
        {
            InitializeComponent();

            UIHelpers.LoadImage(pbLeft, @"UI\Resources\images\envir.jpg", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(pbLogo, @"UI\Resources\images\logo.png", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(pbShow, @"UI\Resources\icons\eye.png", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(pbShow1, @"UI\Resources\icons\eye.png", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(pbBanner, @"UI\Resources\images\envir.jpg", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(pbFaceid, @"UI\Resources\icons\faceid.png", PictureBoxSizeMode.Zoom);
            UIHelpers.LoadImage(pbRightCorner, @"UI\Resources\images\envir.jpg", PictureBoxSizeMode.StretchImage);
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            UIHelpers.RoundPictureBoxLeftCorners(pbLeft, 25);
            pbLogo.Parent = pbLeft;

            // Tạo vùng cong cho pbLeft
            using (GraphicsPath path = new GraphicsPath())
            {
                Rectangle rect = pbLeft.ClientRectangle;
                int cornerRadiusTopLeft = 20;
                int cornerRadiusTopRight = 150;
                int cornerRadiusBottomLeft = 150;
                int cornerRadiusBottomRight = 30;

                path.StartFigure();
                path.AddArc(rect.X, rect.Y, cornerRadiusTopLeft * 2, cornerRadiusTopLeft * 2, 180, 90);
                path.AddLine(rect.X + cornerRadiusTopLeft, rect.Y, rect.Right - cornerRadiusTopRight, rect.Y);
                path.AddArc(rect.Right - cornerRadiusTopRight * 2, rect.Y, cornerRadiusTopRight * 2, cornerRadiusTopRight * 2, 270, 90);
                path.AddLine(rect.Right, rect.Y + cornerRadiusTopRight, rect.Right, rect.Bottom - cornerRadiusBottomRight);
                path.AddArc(rect.Right - cornerRadiusBottomRight * 2, rect.Bottom - cornerRadiusBottomRight * 2, cornerRadiusBottomRight * 2, cornerRadiusBottomRight * 2, 0, 90);
                path.AddLine(rect.Right - cornerRadiusBottomRight, rect.Bottom, rect.X + cornerRadiusBottomLeft, rect.Bottom);
                path.AddArc(rect.X, rect.Bottom - cornerRadiusBottomLeft * 2, cornerRadiusBottomLeft * 2, cornerRadiusBottomLeft * 2, 90, 90);
                path.CloseFigure();

                pbLeft.Region = new Region(path);
            }

            // Tạo vùng cho pbRightCorner
            using (GraphicsPath pathRightCorner = new GraphicsPath())
            {
                Rectangle rectRightCorner = new Rectangle(0, 0, pbRightCorner.Width, pbRightCorner.Height);
                int cornerRadius = 150;

                pathRightCorner.StartFigure();
                pathRightCorner.AddArc(rectRightCorner.Width - cornerRadius * 2, rectRightCorner.Y, cornerRadius * 2, cornerRadius * 2, 270, 90);
                pathRightCorner.AddLine(rectRightCorner.Width, rectRightCorner.Y + cornerRadius, rectRightCorner.Width, 0);
                pathRightCorner.AddLine(rectRightCorner.Width, 0, rectRightCorner.Width - cornerRadius, 0);
                pathRightCorner.CloseFigure();

                pbRightCorner.Region = new Region(pathRightCorner);
            }

            // Điều chỉnh vị trí các điều khiển trong pbLeft
            label1.Location = new Point(label1.Location.X, label1.Location.Y);
            label2.Location = new Point(label2.Location.X, label2.Location.Y);
            label3.Location = new Point(label3.Location.X, label3.Location.Y);
            pbLogo.Location = new Point(pbLogo.Location.X, pbLogo.Location.Y);

            rpBanner.BackColor = Color.Transparent;
            rpBanner.FillColor = Color.FromArgb(180, 255, 255, 255);
            rpBanner.Parent = pbBanner;
            rpBanner.BringToFront();

            // Lưu vị trí và kích thước gốc của btnLogin
            originalBtnLoginLocation = btnLogin.Location;
            originalBtnLoginSize = btnLogin.Size;

            // Đảm bảo rpPhone không hiển thị khi khởi động
            rpPhone.Visible = false;

            // Thêm sự kiện cho btnLogin khi nhấn "Gửi OTP"
            //btnLogin.Click += (s, ev) =>
            //{
                //if (btnLogin.Text == "Gửi OTP ⟶")
                //{
                //    // Chuyển sang chế độ nhập OTP
                //    label4.Text = "NHẬP MÃ OTP";
                //    label5.Text = "Nhập mã OTP được gửi đến số điện thoại của bạn.";
                //    rpPhone.Visible = false;

                //    // Tạo 6 ô input cho OTP
                //    int otpBoxWidth = 40;
                //    int otpBoxHeight = 40;
                //    int spacing = 10;
                //    int startX = rpPhone.Location.X + (rpPhone.Width - (6 * otpBoxWidth + 5 * spacing)) / 2;
                //    int startY = rpPhone.Location.Y;
                //    otpBoxes.Clear();

                //    for (int i = 0; i < 6; i++)
                //    {
                //        PlaceholderTextBox otpBox = new PlaceholderTextBox
                //        {
                //            Size = new Size(otpBoxWidth, otpBoxHeight),
                //            Location = new Point(startX + i * (otpBoxWidth + spacing), startY),
                //            BorderRadius = 5,
                //            BorderSize = 1,
                //            BorderColor = Color.Gray,
                //            Placeholder = "",
                //            MaxLength = 1,
                //            Font = new Font("Segoe UI", 12F, FontStyle.Bold),
                //            TextAlign = HorizontalAlignment.Center,
                //            BackColor = Color.FromArgb(103, 142, 65),
                //            ForeColor = Color.White // Thay đổi màu chữ thành trắng
                //        };
                //        // Ép buộc màu chữ trắng ngay cả khi focus hoặc nhập
                //        otpBox.Enter += (s2, e2) => otpBox.ForeColor = Color.White;
                //        otpBox.Leave += (s2, e2) => otpBox.ForeColor = Color.White;
                //        otpBox.KeyPress += (s2, e2) =>
                //        {
                //            if (!char.IsDigit(e2.KeyChar) && e2.KeyChar != (char)Keys.Back)
                //            {
                //                e2.Handled = true;
                //            }
                //            if (char.IsDigit(e2.KeyChar) && otpBox.TextLength == 1)
                //            {
                //                e2.Handled = true;
                //                if (i < 5)
                //                {
                //                    rpBanner.Controls[i + 1].Focus();
                //                }
                //            }
                //            otpBox.ForeColor = Color.White;
                //        };
                //        otpBoxes.Add(otpBox);  // Thêm vào danh sách
                //        rpBanner.Controls.Add(otpBox);
                //        otpBox.BringToFront();
                //    }

                //    // Cập nhật btnLogin thành "Xác nhận OTP"
                //    btnLogin.Text = "XÁC NHẬN";
                //    btnLogin.Location = new Point(originalBtnLoginLocation.X, startY + otpBoxHeight + 20); // Giữ Y tương đối

                //    //Label lblResendOtp = new Label
                //    //{
                //    lblResendOtp.Text = "⟲ Gửi lại OTP";
                //    lblResendOtp.Font = new Font("Segoe UI", 9f, FontStyle.Bold);
                //    lblResendOtp.ForeColor = SystemColors.ControlText;
                //    lblResendOtp.Location = new Point(btnLogin.Location.X + btnLogin.Width + 50, btnLogin.Location.Y + 10); // Dịch sang phải thêm một chút (+20 thay vì +10)
                //    lblResendOtp.Cursor = Cursors.Hand;
                //    lblResendOtp.AutoSize = true;
                //    //};
                //    lblResendOtp.Click += (s2, e2) => MessageBox.Show("OTP đã được gửi lại!");
                //    rpBanner.Controls.Add(lblResendOtp);
                //    lblResendOtp.BringToFront();
                //}
            //};
        }

        private void pbLeft_Click(object sender, EventArgs e)
        {
        }

        private void label5_Click(object sender, EventArgs e)
        {
        }

        private void label6_Click(object sender, EventArgs e)
        {
            // Cập nhật giao diện cho chế độ "Quên mật khẩu"
            label4.Text = "QUÊN MẬT KHẨU";
            label5.Text = "Nhập số điện thoại của bạn để lấy lại mật khẩu.";
            rpPassword.Visible = false;
            lForgotPass.Visible = false;
            pbFaceid.Visible = false;
            rpUsername.Visible = false;
            rpPhone.Visible = true; // Hiển thị rpPhone thay thế rpUsername

            // Thay đổi btnLogin thành "Gửi OTP" và chỉ di chuyển trục Y
            btnLogin.Text = "Gửi OTP ⟶";
            btnLogin.Location = new Point(originalBtnLoginLocation.X, rpPassword.Location.Y + 10); // Giữ X, đổi Y
            btnLogin.BringToFront();
            lBack.Text = "⟵ Đăng nhập";
        }

        private void label7_Click(object sender, EventArgs e)
        {
            if (btnLogin.Text == "Gửi OTP ⟶")
            {
                // Khôi phục giao diện đăng nhập
                label4.Text = "ĐĂNG NHẬP";
                label5.Text = "Đăng nhập vào tài khoản của bạn để tiếp tục";
                rpPassword.Visible = true;
                lForgotPass.Visible = true;
                pbFaceid.Visible = true;
                rpUsername.Visible = true;
                rpPhone.Visible = false; // Ẩn rpPhone
                // Khôi phục btnLogin về gốc
                btnLogin.Text = "Đăng nhập ⟶";
                btnLogin.Location = originalBtnLoginLocation; // Khôi phục vị trí gốc
                btnLogin.Size = originalBtnLoginSize; // Khôi phục kích thước gốc
                lBack.Text = "⟵ Quay lại";
            }
            else if (btnLogin.Text == "XÁC NHẬN")
            {
                label4.Text = "QUÊN MẬT KHẨU";
                label5.Text = "Nhập số điện thoại của bạn để lấy lại mật khẩu.";
                lBack.Text = "⟵ Quay lại";
                lblResendOtp.Visible = false;
                btnLogin.Text = "Gửi OTP ⟶";
                btnLogin.Location = new Point(originalBtnLoginLocation.X, rpPassword.Location.Y + 10);
                rpPhone.Visible = true;
                // Ẩn các otpBox
                foreach (var box in otpBoxes)
                {
                    box.Visible = false;
                }
            }
            else if(btnLogin.Text == "ĐẶT LẠI")
            {
                // Chuyển sang chế độ nhập OTP
                label4.Text = "NHẬP MÃ OTP";
                label5.Text = "Nhập mã OTP được gửi đến số điện thoại của bạn.";
                lblResendOtp.Visible = true;
                btnLogin.Text = "XÁC NHẬN";
                foreach (var box in otpBoxes)
                {
                    box.Visible = true;
                }
                rpNewPassword.Visible = false;
                rpConfirmPassword.Visible = false;
                // Cập nhật btnLogin thành "Xác nhận OTP"
                btnLogin.Location = new Point(originalBtnLoginLocation.X, rpPhone.Location.Y + 40 + 20); // Giữ Y tương đối

            }

        }

        private void pbRightCorner_Click(object sender, EventArgs e)
        {
        }

        private void btnLogin_Click(object sender, EventArgs e)
        {
            if (btnLogin.Text == "Gửi OTP ⟶")
            {
                // Chuyển sang chế độ nhập OTP
                label4.Text = "NHẬP MÃ OTP";
                label5.Text = "Nhập mã OTP được gửi đến số điện thoại của bạn.";
                rpPhone.Visible = false;
                lblResendOtp.Visible = true;

                // Tạo 6 ô input cho OTP
                int otpBoxWidth = 40;
                int otpBoxHeight = 40;
                int spacing = 10;
                int startX = rpPhone.Location.X + (rpPhone.Width - (6 * otpBoxWidth + 5 * spacing)) / 2;
                int startY = rpPhone.Location.Y;
                otpBoxes.Clear();

                for (int i = 0; i < 6; i++)
                {
                    PlaceholderTextBox otpBox = new PlaceholderTextBox
                    {
                        Size = new Size(otpBoxWidth, otpBoxHeight),
                        Location = new Point(startX + i * (otpBoxWidth + spacing), startY),
                        BorderRadius = 5,
                        BorderSize = 1,
                        BorderColor = Color.Gray,
                        Placeholder = "",
                        MaxLength = 1,
                        Font = new Font("Segoe UI", 12F, FontStyle.Bold),
                        TextAlign = HorizontalAlignment.Center,
                        BackColor = Color.FromArgb(153, 192, 115),
                        ForeColor = Color.White // Thay đổi màu chữ thành trắng
                    };
                    // Ép buộc màu chữ trắng ngay cả khi focus hoặc nhập
                    otpBox.Enter += (s2, e2) => otpBox.ForeColor = Color.White;
                    otpBox.Leave += (s2, e2) => otpBox.ForeColor = Color.White;
                    otpBox.KeyPress += (s2, e2) =>
                    {
                        if (!char.IsDigit(e2.KeyChar) && e2.KeyChar != (char)Keys.Back)
                        {
                            e2.Handled = true;
                        }
                        if (char.IsDigit(e2.KeyChar) && otpBox.TextLength == 1)
                        {
                            e2.Handled = true;
                            if (i < 5)
                            {
                                rpBanner.Controls[i + 1].Focus();
                            }
                        }
                        otpBox.ForeColor = Color.White;
                    };
                    otpBoxes.Add(otpBox);  // Thêm vào danh sách
                    rpBanner.Controls.Add(otpBox);
                    otpBox.BringToFront();
                }

                // Cập nhật btnLogin thành "Xác nhận OTP"
                btnLogin.Text = "XÁC NHẬN";
                btnLogin.Location = new Point(originalBtnLoginLocation.X, startY + otpBoxHeight + 20); // Giữ Y tương đối

                //Label lblResendOtp = new Label
                //{
                lblResendOtp.Text = "⟲ Gửi lại OTP";
                lblResendOtp.Font = new Font("Segoe UI", 9f, FontStyle.Bold);
                lblResendOtp.ForeColor = SystemColors.ControlText;
                lblResendOtp.Location = new Point(btnLogin.Location.X + btnLogin.Width + 50, btnLogin.Location.Y + 10); // Dịch sang phải thêm một chút (+20 thay vì +10)
                lblResendOtp.Cursor = Cursors.Hand;
                lblResendOtp.AutoSize = true;
                //};
                lblResendOtp.Click += (s2, e2) => MessageBox.Show("OTP đã được gửi lại!");
                rpBanner.Controls.Add(lblResendOtp);
                lblResendOtp.BringToFront();
                lBack.Text = "⟵ Quay lại";
            }
            else if (btnLogin.Text == "XÁC NHẬN")
            {
                // Chuyển sang trạng thái đặt lại mật khẩu
                btnLogin.Text = "ĐẶT LẠI";
                label4.Text = "ĐẶT LẠI MẬT KHẨU";
                label5.Text = "Đặt lại mật khẩu mới để hoàn thành";

                // Ẩn các otpBox và lblResendOtp
                foreach (var box in otpBoxes)
                {
                    box.Visible = false;
                }
                lblResendOtp.Visible = false;
                rpConfirmPassword.Visible = true;
                rpNewPassword.Visible = true;

                // Khôi phục vị trí btnLogin về gốc (đã điều chỉnh Y - 25 theo yêu cầu)
                btnLogin.Location = new Point(originalBtnLoginLocation.X, originalBtnLoginLocation.Y - 25);

                rpNewPassword.Location = rpUsername.Location;
                rpNewPassword.Size = rpPassword.Size;
                rpNewPassword.BackColor = rpPassword.BackColor;
                rpNewPassword.BorderColor = rpPassword.BorderColor;
                rpNewPassword.BorderRadius = rpPassword.BorderRadius;
                rpNewPassword.BorderSize = rpPassword.BorderSize;
                rpNewPassword.ShadowColor = rpPassword.ShadowColor;
                rpNewPassword.ShadowSize = rpPassword.ShadowSize;
                PlaceholderTextBox ptbNewPassword = new PlaceholderTextBox
                {
                    Location = ptbPassword.Location,
                    Size = ptbPassword.Size,
                    BackColor = ptbPassword.BackColor,
                    BorderColor = ptbPassword.BorderColor,
                    BorderRadius = ptbPassword.BorderRadius,
                    BorderSize = ptbPassword.BorderSize,
                    BorderStyle = ptbPassword.BorderStyle,
                    Font = ptbPassword.Font,
                    ForeColor = ptbPassword.ForeColor,
                    Placeholder = "Mật khẩu mới",
                    PlaceholderText = "Mật khẩu mới",
                    TextPadding = ptbPassword.TextPadding
                };
                // Thêm sự kiện để bật PasswordChar khi focus
                ptbNewPassword.Enter += (s2, e2) =>
                {
                    if (string.IsNullOrEmpty(ptbNewPassword.Text) || ptbNewPassword.Text == ptbNewPassword.Placeholder)
                    {
                        ptbNewPassword.Text = "";
                        ptbNewPassword.ForeColor = ptbPassword.ForeColor; // Đặt màu chữ khi nhập
                    }
                    ptbNewPassword.PasswordChar = '*';
                };
                // Khôi phục placeholder khi rời ô
                ptbNewPassword.Leave += (s2, e2) =>
                {
                    if (string.IsNullOrEmpty(ptbNewPassword.Text))
                    {
                        ptbNewPassword.Text = ptbNewPassword.Placeholder;
                        ptbNewPassword.ForeColor = Color.Gray; // Màu placeholder
                        ptbNewPassword.PasswordChar = '\0'; // Tắt PasswordChar
                    }
                };
                PictureBox pbShowNew = new PictureBox
                {
                    Location = pbShow.Location,
                    Size = pbShow.Size,
                    Image = pbShow.Image,
                    SizeMode = pbShow.SizeMode
                };
                pbShowNew.Click += (s2, e2) =>
                {
                    ptbNewPassword.PasswordChar = (ptbNewPassword.PasswordChar == '*') ? '\0' : '*';
                };
                rpNewPassword.Controls.Add(ptbNewPassword);
                rpNewPassword.Controls.Add(pbShowNew);

                // Tạo động rpConfirmPassword tại vị trí rpPassword (cho "Xác nhận mật khẩu")

                rpConfirmPassword.Location = rpPassword.Location;
                rpConfirmPassword.Size = rpPassword.Size;
                rpConfirmPassword.BackColor = rpPassword.BackColor;
                rpConfirmPassword.BorderColor = rpPassword.BorderColor;
                rpConfirmPassword.BorderRadius = rpPassword.BorderRadius;
                rpConfirmPassword.BorderSize = rpPassword.BorderSize;
                rpConfirmPassword.ShadowColor = rpPassword.ShadowColor;
                rpConfirmPassword.ShadowSize = rpPassword.ShadowSize;

                PlaceholderTextBox ptbConfirmPassword = new PlaceholderTextBox
                {
                    Location = ptbPassword.Location,
                    Size = ptbPassword.Size,
                    BackColor = ptbPassword.BackColor,
                    BorderColor = ptbPassword.BorderColor,
                    BorderRadius = ptbPassword.BorderRadius,
                    BorderSize = ptbPassword.BorderSize,
                    BorderStyle = ptbPassword.BorderStyle,
                    Font = ptbPassword.Font,
                    ForeColor = ptbPassword.ForeColor,
                    Placeholder = "Xác nhận mật khẩu",
                    PlaceholderText = "Xác nhận mật khẩu",
                    TextPadding = ptbPassword.TextPadding
                };
                ptbConfirmPassword.Enter += (s2, e2) =>
                {
                    if (string.IsNullOrEmpty(ptbConfirmPassword.Text) || ptbConfirmPassword.Text == ptbConfirmPassword.Placeholder)
                    {
                        ptbConfirmPassword.Text = "";
                        ptbConfirmPassword.ForeColor = ptbPassword.ForeColor;
                    }
                    ptbConfirmPassword.PasswordChar = '*';
                };
                ptbConfirmPassword.Leave += (s2, e2) =>
                {
                    if (string.IsNullOrEmpty(ptbConfirmPassword.Text))
                    {
                        ptbConfirmPassword.Text = ptbConfirmPassword.Placeholder;
                        ptbConfirmPassword.ForeColor = Color.Gray;
                        ptbConfirmPassword.PasswordChar = '\0';
                    }
                };
                PictureBox pbShowConfirm = new PictureBox
                {
                    Location = pbShow.Location,
                    Size = pbShow.Size,
                    Image = pbShow.Image,
                    SizeMode = pbShow.SizeMode
                };
                pbShowConfirm.Click += (s2, e2) =>
                {
                    ptbConfirmPassword.PasswordChar = (ptbConfirmPassword.PasswordChar == '*') ? '\0' : '*';
                };
                rpConfirmPassword.Controls.Add(ptbConfirmPassword);
                rpConfirmPassword.Controls.Add(pbShowConfirm);

                // Thêm hai panel mới vào rpBanner
                rpBanner.Controls.Add(rpNewPassword);
                rpBanner.Controls.Add(rpConfirmPassword);
                rpNewPassword.BringToFront();
                rpConfirmPassword.BringToFront();
            }
            else
            {

            }    
        }
    }
}

        