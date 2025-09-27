using EMC.DAO;
using EMC.DTO;
using EMC.UI.Helpers;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace EMC.UI.Forms
{
    public partial class fPlanning : Form
    {
        private bool sidebarVisible = true;
        private const int SIDEBAR_WIDTH = 288;
        private const int SIDEBAR_COLLAPSED_WIDTH = 80;
        public fPlanning()
        {
            InitializeComponent();
            LoadSampleData();
            InitializeDataGridViewEvents();
            this.WindowState = FormWindowState.Maximized;
            this.Resize += fPlanning_Resize;
        }

        private void fPlanning_Load(object sender, EventArgs e)
        {
            UIHelpers.LoadImage(cpbLogo, @"UI\Resources\images\logo.png", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(cpbAvatar, @"UI\Resources\uploads\anhthe.jpg", PictureBoxSizeMode.StretchImage);


            guna2ComboBox1.Items.Clear();
            guna2ComboBox1.Items.Add("Ngày tạo");
            guna2ComboBox1.Items.Add("↑ Tăng dần");
            guna2ComboBox1.Items.Add("↓ Giảm dần");
            guna2ComboBox1.StartIndex = 0;   // Không chọn item nào
        }

        private void fPlanning_Resize(object sender, EventArgs e)
        {
            int paddingRight = 20; // Khoảng cách từ cạnh phải
            roundedButton2.Left = CustomGradientPanel1.ClientSize.Width - roundedButton2.Width - paddingRight;
            roundedButton2.Top = 11; // Giữ nguyên khoảng cách từ trên

            // Đặt khoảng cách đối xứng ngang và dọc ban đầu (25px trái, phải, và dưới)
            int padding = 25;
            dgvSamples.Left = padding;
            dgvSamples.Width = CustomGradientPanel1.ClientSize.Width - (2 * padding);
            dgvSamples.Height = CustomGradientPanel1.ClientSize.Height - dgvSamples.Top - padding;

            // Giữ khoảng cách giữa comboBox và button (ví dụ 20px)
            int spacing = 20;
            guna2ComboBox1.Top = roundedButton2.Top;
            guna2ComboBox1.Left = roundedButton2.Left - guna2ComboBox1.Width - spacing;

            //// Làm cho các cột tự giãn theo chiều rộng của dgvCustomers
            dgvSamples.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill;

            // Đặt chiều rộng cố định cho cột ThaoTac
            dgvSamples.Columns["ThaoTac"].Width = 150;

            dgvSamples.CellBorderStyle = DataGridViewCellBorderStyle.None;
            dgvSamples.ColumnHeadersBorderStyle = DataGridViewHeaderBorderStyle.None;
            dgvSamples.RowHeadersBorderStyle = DataGridViewHeaderBorderStyle.None;
        }

        private void Sidebar_Resize(object sender, EventArgs e)
        {
            // Cập nhật vị trí và kích thước của CustomGradientPanel1
            CustomGradientPanel1.Left = pSidebar.Width;
            CustomGradientPanel1.Width = this.ClientSize.Width - pSidebar.Width;
            CustomGradientPanel1.Height = this.ClientSize.Height;

            if (!sidebarVisible)
            {
                roundedButton1.Location = new Point(11, 15); // Vị trí cố định
                roundedButton1.BringToFront(); // Đưa nút lên trên cùng

                // Cập nhật vị trí label5 (Quản lý hợp đồng) khi sidebar thu
                label5.Left = pSidebar.Width + 30; // Tăng khoảng cách từ 10 lên 30
                label5.Top = 11; // Cùng vị trí với thanh tìm kiếm
                label5.Visible = true; // Vẫn hiển thị label5
                CustomGradientPanel1.Width = this.ClientSize.Width - pSidebar.Width - 75;
            }
            else
            {
                label5.Visible = true; // Hiển thị lại label5 khi sidebar mở rộng
                label5.Location = new Point(pSidebar.Width + 25, 11); // Đặt label5 cùng vị trí với thanh tìm kiếm
                CustomGradientPanel1.Width = this.ClientSize.Width - pSidebar.Width;
            }

            // Cập nhật vị trí nút "Thêm hợp đồng"
            int paddingRight = 20;
            roundedButton2.Left = CustomGradientPanel1.ClientSize.Width - roundedButton2.Width - paddingRight;
            roundedButton2.Top = 11;

            // Cập nhật vị trí thanh tìm kiếm với padding khác nhau
            int searchPadding = sidebarVisible ? 25 : 30; // Tăng từ 10 lên 30 khi thu gọn
            roundedTextBox1.Left = searchPadding;
            roundedTextBox1.Top = 11;

            // Cập nhật kích thước DataGridView với padding bằng nhau hai bên
            int gridPadding = 25; // Giữ nguyên padding hai bên
            dgvSamples.Left = gridPadding;
            dgvSamples.Width = CustomGradientPanel1.ClientSize.Width - (2 * gridPadding); // Trừ đi padding hai bên
            dgvSamples.Height = CustomGradientPanel1.ClientSize.Height - dgvSamples.Top - gridPadding;

            // Thiết lập các cột tự động giãn đều
            dgvSamples.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill;

            // Cố định chiều rộng cột ThaoTac
            dgvSamples.Columns["ThaoTac"].Width = 150;

            // Ẩn đường viền
            dgvSamples.CellBorderStyle = DataGridViewCellBorderStyle.None;
            dgvSamples.ColumnHeadersBorderStyle = DataGridViewHeaderBorderStyle.None;
            dgvSamples.RowHeadersBorderStyle = DataGridViewHeaderBorderStyle.None;
        }

        private void InitializeDataGridViewEvents()
        {
            dgvSamples.CellPainting += dgvSamples_CellPainting;
        }

        private void dgvSamples_CellPainting(object sender, DataGridViewCellPaintingEventArgs e)
        {
            if (e.ColumnIndex == dgvSamples.Columns["ThaoTac"].Index && e.RowIndex >= 0)
            {
                e.Handled = true;
                e.PaintBackground(e.CellBounds, true);

                // Sử dụng cùng style như fBusiness
                e.Graphics.SmoothingMode = System.Drawing.Drawing2D.SmoothingMode.AntiAlias;
                e.Graphics.TextRenderingHint = System.Drawing.Text.TextRenderingHint.ClearTypeGridFit;

                int iconWidth = 35;
                int iconHeight = 25;
                int paddingLeft = 10;
                int spacing = 40;
                int cornerRadius = 8; // Bán kính bo tròn

                int x1 = e.CellBounds.Left + paddingLeft;
                int x2 = x1 + spacing;
                int x3 = x2 + spacing;
                int y = e.CellBounds.Top + (e.CellBounds.Height - iconHeight) / 2;

                Rectangle r1 = new Rectangle(x1, y, iconWidth, iconHeight);
                Rectangle r2 = new Rectangle(x2, y, iconWidth, iconHeight);
                Rectangle r3 = new Rectangle(x3, y, iconWidth, iconHeight);

                // Phương thức helper để vẽ hình chữ nhật bo tròn
                System.Drawing.Drawing2D.GraphicsPath GetRoundedRectPath(Rectangle rect, int radius)
                {
                    System.Drawing.Drawing2D.GraphicsPath path = new System.Drawing.Drawing2D.GraphicsPath();
                    float r = radius;
                    path.AddArc(rect.X, rect.Y, r, r, 180, 90);
                    path.AddArc(rect.X + rect.Width - r, rect.Y, r, r, 270, 90);
                    path.AddArc(rect.X + rect.Width - r, rect.Y + rect.Height - r, r, r, 0, 90);
                    path.AddArc(rect.X, rect.Y + rect.Height - r, r, r, 90, 90);
                    path.CloseAllFigures();
                    return path;
                }

                // Vẽ background và border cho các nút với góc bo tròn
                using (SolidBrush bg = new SolidBrush(Color.FromArgb(240, 240, 240)))
                using (Pen border = new Pen(Color.FromArgb(120, 120, 120), 1))
                {
                    // Nút View (màu xanh cyan)
                    using (var path1 = GetRoundedRectPath(r1, cornerRadius))
                    using (SolidBrush viewBg = new SolidBrush(Color.FromArgb(240, 240, 240)))
                    {
                        e.Graphics.FillPath(viewBg, path1);
                        e.Graphics.DrawPath(border, path1);
                    }

                    // Nút Edit (màu vàng)
                    using (var path2 = GetRoundedRectPath(r2, cornerRadius))
                    using (SolidBrush editBg = new SolidBrush(Color.FromArgb(240, 240, 240)))
                    {
                        e.Graphics.FillPath(editBg, path2);
                        e.Graphics.DrawPath(border, path2);
                    }

                    // Nút Delete (màu đỏ)
                    using (var path3 = GetRoundedRectPath(r3, cornerRadius))
                    using (SolidBrush deleteBg = new SolidBrush(Color.FromArgb(240, 240, 240)))
                    {
                        e.Graphics.FillPath(deleteBg, path3);
                        e.Graphics.DrawPath(border, path3);
                    }
                }

                // Vẽ icons
                string iconView = "👁";
                string iconEdit = "✏";
                string iconDelete = "🗑";

                using (Font iconFont = new Font("Segoe UI Emoji", 12, FontStyle.Regular, GraphicsUnit.Pixel))
                using (SolidBrush iconBrush = new SolidBrush(Color.Black)) // Đổi màu icon thành trắng để nổi bật
                {
                    SizeF s;

                    s = e.Graphics.MeasureString(iconView, iconFont);
                    e.Graphics.DrawString(iconView, iconFont, iconBrush,
                        r1.Left + (r1.Width - s.Width) / 2f,
                        r1.Top + (r1.Height - s.Height) / 2f);

                    s = e.Graphics.MeasureString(iconEdit, iconFont);
                    e.Graphics.DrawString(iconEdit, iconFont, iconBrush,
                        r2.Left + (r2.Width - s.Width) / 2f,
                        r2.Top + (r2.Height - s.Height) / 2f);

                    s = e.Graphics.MeasureString(iconDelete, iconFont);
                    e.Graphics.DrawString(iconDelete, iconFont, iconBrush,
                        r3.Left + (r3.Width - s.Width) / 2f,
                        r3.Top + (r3.Height - s.Height) / 2f);
                }
            }
        }

        private void LoadSampleData()
        {
            dgvSamples.Rows.Clear();

            // Debug để kiểm tra cột
            if (dgvSamples.Columns["contractCode"] == null)
            {
                MessageBox.Show("Cột contractCode không tồn tại!");
                return;
            }

            // Load từ DB qua DAO
            List<Sample> samples = SampleDAO.Instance.GetListSamples("created_at");

            foreach (Sample sample in samples)
            {
                dgvSamples.Rows.Add(
                    sample.ContractID,          // contractCode
                    sample.SampleCode,          // sampleCode
                    sample.SampleType,          // sampleType
                    sample.Description,         // sampleDescription
                    sample.Location,            // sampleLocation
                    sample.CreatedAt.ToString(),// createdAt (cần thêm thuộc tính created_at vào Sample)
                    "",                         // sampleStatus (có thể lấy từ bảng khác)
                    ""                          // ThaoTac (có thể thêm logic sau)
                );
            }

            // Gán HeaderText sau khi kiểm tra cột
            dgvSamples.Columns["contractCode"].HeaderText = "Mã hợp đồng";
            dgvSamples.Columns["sampleCode"].HeaderText = "Mã mẫu";
            dgvSamples.Columns["sampleType"].HeaderText = "Loại mẫu";
            dgvSamples.Columns["sampleDescription"].HeaderText = "Mô tả";
            dgvSamples.Columns["createdAt"].HeaderText = "Ngày tạo";
            dgvSamples.Columns["sampleLocation"].HeaderText = "Địa điểm";
            //dgvSamples.Columns["sampleStatus"].HeaderText = "Trạng thái";
            dgvSamples.Columns["ThaoTac"].HeaderText = "Thao tác";

            // Áp dụng style (nếu cần)
            foreach (DataGridViewRow row in dgvSamples.Rows)
            {
                row.Cells["createdAt"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
            }
        }


        private void guna2ComboBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            if (guna2ComboBox1.SelectedIndex == 0)
            {
                // Nếu là "Ngày tạo", không làm gì
                return;
            }
        }

        private void roundedButton1_Click(object sender, EventArgs e)
        {
            sidebarVisible = !sidebarVisible;

            if (sidebarVisible)
            {
                // Mở rộng sidebar
                pSidebar.Width = SIDEBAR_WIDTH;
                cpbLogo.Visible = true;
                line1.Visible = true;
                label1.Visible = true;
                label2.Visible = true;
                label3.Visible = true;
                label4.Visible = true;
                roundedButton1.Text = "☰";
                roundedButton1.BorderSize = 1;

                // Hiện lại màu nền khi mở rộng
                pSidebar.BackColor = Color.FromArgb(45, 55, 72);
            }
            else
            {
                // Thu sidebar - chỉ để lại nút menu
                pSidebar.Width = SIDEBAR_COLLAPSED_WIDTH;
                cpbLogo.Visible = false;
                line1.Visible = false;
                label1.Visible = false;
                label2.Visible = false;
                label3.Visible = false;
                label4.Visible = false;
                roundedButton1.Text = "☰";

                // Xóa màu nền để chỉ còn nút menu
                pSidebar.BackColor = Color.Transparent;
                roundedButton1.BorderSize = 0;

            }

            // Gọi sự kiện Resize để cập nhật lại giao diện
            Sidebar_Resize(sender, e);
        }
    }
}
