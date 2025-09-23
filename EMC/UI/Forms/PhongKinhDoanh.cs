using EMC.UI.Helpers;
using System;
using System.Drawing;
using System.Windows.Forms;

namespace EMC.UI.Forms
{
    public partial class PhongKinhDoanh : Form
    {
        private bool sidebarVisible = true;
        private const int SIDEBAR_WIDTH = 288;
        private const int SIDEBAR_COLLAPSED_WIDTH = 80;

        public PhongKinhDoanh()
        {
            InitializeComponent();
            LoadSampleData();
            InitializeDataGridViewEvents();
            this.WindowState = FormWindowState.Maximized;
            this.Resize += PhongKinhDoanh_Resize;
        }

        private void PhongKinhDoanh_Load(object sender, EventArgs e)
        {
            UIHelpers.LoadImage(cpbLogo, @"UI\Resources\images\logo.png", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(cpbAvatar, @"UI\Resources\uploads\anhthe.jpg", PictureBoxSizeMode.StretchImage);
        }

        private void PhongKinhDoanh_Resize(object sender, EventArgs e)
        {
            int paddingRight = 20; // Khoảng cách từ cạnh phải
            roundedButton2.Left = CustomGradientPanel1.ClientSize.Width - roundedButton2.Width - paddingRight;
            roundedButton2.Top = 11; // Giữ nguyên khoảng cách từ trên

            // Đặt khoảng cách đối xứng ngang và dọc ban đầu (25px trái, phải, và dưới)
            int padding = 25;
            dgvCustomers.Left = padding;
            dgvCustomers.Width = CustomGradientPanel1.ClientSize.Width - (2 * padding);
            dgvCustomers.Height = CustomGradientPanel1.ClientSize.Height - dgvCustomers.Top - padding;

            //// Làm cho các cột tự giãn theo chiều rộng của dgvCustomers
            dgvCustomers.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill;

            // Đặt chiều rộng cố định cho cột ThaoTac
            dgvCustomers.Columns["ThaoTac"].Width = 150;

            dgvCustomers.CellBorderStyle = DataGridViewCellBorderStyle.None;
            dgvCustomers.ColumnHeadersBorderStyle = DataGridViewHeaderBorderStyle.None;
            dgvCustomers.RowHeadersBorderStyle = DataGridViewHeaderBorderStyle.None;
        }

        private void Thanhtimkiem_GotFocus(object sender, EventArgs e)
        {
            if (Thanhtimkiem.Text == "Tìm kiếm theo mã hợp đồng, tên khách hàng..." ||
                Thanhtimkiem.Text == "Tìm kiếm theo mã doanh nghiệp, tên doanh nghiệp...")
            {
                Thanhtimkiem.Text = "";
                Thanhtimkiem.ForeColor = Color.Black;
            }
        }

        private void Thanhtimkiem_LostFocus(object sender, EventArgs e)
        {
            if (string.IsNullOrWhiteSpace(Thanhtimkiem.Text))
            {
                if (dgvCustomers.Columns["MaHopDong"].HeaderText == "Mã DN")
                    Thanhtimkiem.Text = "Tìm kiếm theo mã doanh nghiệp, tên doanh nghiệp...";
                else
                    Thanhtimkiem.Text = "Tìm kiếm theo mã hợp đồng, tên khách hàng...";
                Thanhtimkiem.ForeColor = Color.Gray;
            }
        }

        private void InitializeDataGridViewEvents()
        {
            dgvCustomers.CellPainting += dgvCustomers_CellPainting;
            dgvCustomers.CellClick += dgvCustomers_CellClick;
        }

        private void dgvCustomers_CellPainting(object sender, DataGridViewCellPaintingEventArgs e)
        {
            if (e.ColumnIndex == dgvCustomers.Columns["ThaoTac"].Index && e.RowIndex >= 0)
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

        private void dgvCustomers_CellClick(object sender, DataGridViewCellEventArgs e)
        {
            if (e.ColumnIndex == dgvCustomers.Columns["ThaoTac"].Index && e.RowIndex >= 0)
            {
                DataGridViewRow selectedRow = dgvCustomers.Rows[e.RowIndex];
                string contractId = selectedRow.Cells["MaHopDong"].Value?.ToString() ?? "";
                string customerName = selectedRow.Cells["TenKhachHang"].Value?.ToString() ?? "";

                // Sử dụng cùng logic click detection như fBusiness
                int iconWidth = 35;
                int paddingLeft = 10;
                int spacing = 45;

                Rectangle cellRect = dgvCustomers.GetCellDisplayRectangle(e.ColumnIndex, e.RowIndex, false);
                Point clickPoint = dgvCustomers.PointToClient(Cursor.Position);
                int relativeX = clickPoint.X - cellRect.X;

                if (relativeX >= paddingLeft && relativeX < paddingLeft + iconWidth)
                {
                    // Nút View
                    ViewContractDetails(contractId, customerName);
                }
                else if (relativeX >= paddingLeft + spacing && relativeX < paddingLeft + spacing + iconWidth)
                {
                    // Nút Edit
                    EditContract(contractId, customerName);
                }
                else if (relativeX >= paddingLeft + 2 * spacing && relativeX < paddingLeft + 2 * spacing + iconWidth)
                {
                    // Nút Delete
                    DeleteContract(contractId, customerName);
                }
            }
        }

        private void ViewContractDetails(string contractId, string customerName)
        {
            MessageBox.Show($"Xem chi tiết hợp đồng:\nMã HĐ: {contractId}\nKhách hàng: {customerName}\n\nChức năng sẽ được phát triển trong phiên bản tương lai!",
                "Chi tiết hợp đồng", MessageBoxButtons.OK, MessageBoxIcon.Information);
        }

        private void EditContract(string contractId, string customerName)
        {
            MessageBox.Show($"Chỉnh sửa hợp đồng:\nMã HĐ: {contractId}\nKhách hàng: {customerName}\n\nChức năng sẽ được phát triển trong phiên bản tương lai!",
                "Chỉnh sửa hợp đồng", MessageBoxButtons.OK, MessageBoxIcon.Information);
        }

        private void DeleteContract(string contractId, string customerName)
        {
            DialogResult result = MessageBox.Show($"Bạn có chắc chắn muốn xóa hợp đồng:\nMã HĐ: {contractId}\nKhách hàng: {customerName}?",
                "Xác nhận xóa", MessageBoxButtons.YesNo, MessageBoxIcon.Warning);

            if (result == DialogResult.Yes)
            {
                for (int i = dgvCustomers.Rows.Count - 1; i >= 0; i--)
                {
                    if (dgvCustomers.Rows[i].Cells["MaHopDong"].Value?.ToString() == contractId)
                    {
                        dgvCustomers.Rows.RemoveAt(i);
                        MessageBox.Show("Đã xóa hợp đồng thành công!", "Thông báo", MessageBoxButtons.OK, MessageBoxIcon.Information);
                        break;
                    }
                }
            }
        }

        private void LoadSampleData()
        {
            dgvCustomers.Rows.Add("HD001", "Công ty TNHH ABC", "0901234567", "abc@company.com", "15/09/2025", "Đang hiệu lực", "", "15/09/2026", "");
            dgvCustomers.Rows.Add("HD002", "Nhà máy Thép DEF", "0912345678", "def@steel.com", "10/09/2025", "Đang hiệu lực", "", "10/09/2026", "");
            dgvCustomers.Rows.Add("HD003", "Khu CN GHI", "0923456789", "ghi@industrial.com", "05/09/2025", "Sắp hết hạn", "05/12/2025", "05/09/2026", "");
            dgvCustomers.Rows.Add("HD004", "Công ty Dệt JKL", "0934567890", "jkl@textile.com", "12/09/2025", "Đang hiệu lực", "", "12/09/2026", "");
            dgvCustomers.Rows.Add("HD005", "Nhà máy Giấy MNO", "0945678901", "mno@paper.com", "08/09/2025", "Hết hạn", "08/11/2025", "08/09/2026", "");

            foreach (DataGridViewRow row in dgvCustomers.Rows)
            {
                string trangThai = row.Cells["TrangThai"].Value?.ToString();

                if (trangThai == "Đang hiệu lực")
                {
                    row.Cells["TrangThai"].Style.BackColor = Color.FromArgb(209, 250, 229);
                    row.Cells["TrangThai"].Style.ForeColor = Color.FromArgb(22, 163, 74);
                }
                else if (trangThai == "Sắp hết hạn")
                {
                    row.Cells["TrangThai"].Style.BackColor = Color.FromArgb(255, 243, 205);
                    row.Cells["TrangThai"].Style.ForeColor = Color.FromArgb(181, 137, 0);
                }
                else if (trangThai == "Hết hạn")
                {
                    row.Cells["TrangThai"].Style.BackColor = Color.FromArgb(254, 226, 226);
                    row.Cells["TrangThai"].Style.ForeColor = Color.FromArgb(185, 28, 28);
                }

                row.Cells["NgayKy"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
                row.Cells["NgayGiaHan"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
                row.Cells["HanHopDong"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
                row.Cells["TrangThai"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
            }
        }

        protected override void OnFormClosed(FormClosedEventArgs e)
        {
            base.OnFormClosed(e);
        }

        private void btnAddnewguest_Click(object sender, EventArgs e)
        {
            MessageBox.Show("Chức năng thêm hợp đồng mới sẽ được phát triển trong phiên bản tương lai!", "Thông báo", MessageBoxButtons.OK, MessageBoxIcon.Information);
        }

        private void guna2Button4_Click(object sender, EventArgs e)
        {
            LoadEnterpriseData();
        }

        private void guna2Button5_Click(object sender, EventArgs e)
        {
            LoadContractData();
        }

        private void userDropdownBtn_Click(object sender, EventArgs e)
        {
            //userDropdownMenu.Show(userDropdownBtn, new Point(0, userDropdownBtn.Height));
        }

        private void viewProfileItem_Click(object sender, EventArgs e)
        {
            MessageBox.Show("Chức năng xem thông tin cá nhân sẽ được phát triển trong phiên bản tương lai!", "Thông báo", MessageBoxButtons.OK, MessageBoxIcon.Information);
        }

        private void logoutItem_Click(object sender, EventArgs e)
        {
            DialogResult result = MessageBox.Show("Bạn có chắc chắn muốn đăng xuất?", "Xác nhận", MessageBoxButtons.YesNo, MessageBoxIcon.Question);
            if (result == DialogResult.Yes)
            {
                this.Close();
            }
        }

        private void LoadEnterpriseData()
        {
            dgvCustomers.Rows.Clear();
            dgvCustomers.Rows.Add("DN001", "Công ty TNHH Công nghệ ABC", "0901234567", "contact@abc-tech.com", "01/01/2020", "Hoạt động", "", "Không giới hạn", "");
            dgvCustomers.Rows.Add("DN002", "Tập đoàn Thép Việt Nam", "0912345678", "info@steelvn.com", "15/03/2018", "Hoạt động", "", "Không giới hạn", "");
            dgvCustomers.Rows.Add("DN003", "Công ty CP Dệt May DEF", "0923456789", "sales@defTextile.com", "10/06/2019", "Tạm ngưng", "01/12/2024", "31/12/2025", "");
            dgvCustomers.Rows.Add("DN004", "Nhà máy Chế biến thực phẩm GHI", "0934567890", "orders@ghifood.com", "22/09/2021", "Hoạt động", "", "Không giới hạn", "");
            dgvCustomers.Rows.Add("DN005", "Công ty TNHH Xây dựng JKL", "0945678901", "project@jklconstruction.com", "05/11/2017", "Ngừng hoạt động", "15/08/2024", "15/08/2025", "");
            dgvCustomers.Rows.Add("DN006", "Tổng công ty Logistics MNO", "0956789012", "logistics@mnogroup.com", "30/04/2020", "Hoạt động", "", "Không giới hạn", "");
            dgvCustomers.Rows.Add("DN007", "Công ty CP Công nghệ thông tin PQR", "0967890123", "support@pqrit.com", "12/02/2022", "Hoạt động", "", "Không giới hạn", "");

            foreach (DataGridViewRow row in dgvCustomers.Rows)
            {
                string trangThai = row.Cells["TrangThai"].Value?.ToString();
                if (trangThai == "Hoạt động")
                {
                    row.Cells["TrangThai"].Style.BackColor = Color.FromArgb(209, 250, 229);
                    row.Cells["TrangThai"].Style.ForeColor = Color.FromArgb(22, 163, 74);
                }
                else if (trangThai == "Tạm ngưng")
                {
                    row.Cells["TrangThai"].Style.BackColor = Color.FromArgb(255, 243, 205);
                    row.Cells["TrangThai"].Style.ForeColor = Color.FromArgb(181, 137, 0);
                }
                else if (trangThai == "Ngừng hoạt động")
                {
                    row.Cells["TrangThai"].Style.BackColor = Color.FromArgb(254, 226, 226);
                    row.Cells["TrangThai"].Style.ForeColor = Color.FromArgb(185, 28, 28);
                }

                row.Cells["NgayKy"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
                row.Cells["NgayGiaHan"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
                row.Cells["HanHopDong"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
                row.Cells["TrangThai"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
            }

            dgvCustomers.Columns["MaHopDong"].HeaderText = "Mã DN";
            dgvCustomers.Columns["TenKhachHang"].HeaderText = "Tên doanh nghiệp";
            dgvCustomers.Columns["NgayKy"].HeaderText = "Ngày thành lập";
            dgvCustomers.Columns["TrangThai"].HeaderText = "Trạng thái";
            dgvCustomers.Columns["NgayGiaHan"].HeaderText = "Ngày cập nhật";
            dgvCustomers.Columns["HanHopDong"].HeaderText = "Thời hạn hoạt động";
            Thanhtimkiem.Text = "Tìm kiếm theo mã doanh nghiệp, tên doanh nghiệp...";
            Thanhtimkiem.ForeColor = Color.Gray;
        }

        private void LoadContractData()
        {
            dgvCustomers.Rows.Clear();
            dgvCustomers.Rows.Add("HD001", "Công ty TNHH ABC", "0901234567", "abc@company.com", "15/09/2025", "Đang hiệu lực", "", "15/09/2026", "");
            dgvCustomers.Rows.Add("HD002", "Nhà máy Thép DEF", "0912345678", "def@steel.com", "10/09/2025", "Đang hiệu lực", "", "10/09/2026", "");
            dgvCustomers.Rows.Add("HD003", "Khu CN GHI", "0923456789", "ghi@industrial.com", "05/09/2025", "Sắp hết hạn", "05/12/2025", "05/09/2026", "");
            dgvCustomers.Rows.Add("HD004", "Công ty Dệt JKL", "0934567890", "jkl@textile.com", "12/09/2025", "Đang hiệu lực", "", "12/09/2026", "");
            dgvCustomers.Rows.Add("HD005", "Nhà máy Giấy MNO", "0945678901", "mno@paper.com", "08/09/2025", "Hết hạn", "08/11/2025", "08/09/2026", "");

            foreach (DataGridViewRow row in dgvCustomers.Rows)
            {
                string trangThai = row.Cells["TrangThai"].Value?.ToString();
                if (trangThai == "Đang hiệu lực")
                {
                    row.Cells["TrangThai"].Style.BackColor = Color.FromArgb(209, 250, 229);
                    row.Cells["TrangThai"].Style.ForeColor = Color.FromArgb(22, 163, 74);
                }
                else if (trangThai == "Sắp hết hạn")
                {
                    row.Cells["TrangThai"].Style.BackColor = Color.FromArgb(255, 243, 205);
                    row.Cells["TrangThai"].Style.ForeColor = Color.FromArgb(181, 137, 0);
                }
                else if (trangThai == "Hết hạn")
                {
                    row.Cells["TrangThai"].Style.BackColor = Color.FromArgb(254, 226, 226);
                    row.Cells["TrangThai"].Style.ForeColor = Color.FromArgb(185, 28, 28);
                }

                row.Cells["NgayKy"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
                row.Cells["NgayGiaHan"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
                row.Cells["HanHopDong"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
                row.Cells["TrangThai"].Style.Alignment = DataGridViewContentAlignment.MiddleCenter;
            }

            dgvCustomers.Columns["MaHopDong"].HeaderText = "Mã hợp đồng";
            dgvCustomers.Columns["TenKhachHang"].HeaderText = "Tên khách hàng";
            dgvCustomers.Columns["NgayKy"].HeaderText = "Ngày ký";
            dgvCustomers.Columns["TrangThai"].HeaderText = "Trạng thái";
            dgvCustomers.Columns["NgayGiaHan"].HeaderText = "Ngày gia hạn";
            dgvCustomers.Columns["HanHopDong"].HeaderText = "Hạn hợp đồng";
            Thanhtimkiem.Text = "Tìm kiếm theo mã hợp đồng, tên khách hàng...";
            Thanhtimkiem.ForeColor = Color.Gray;
        }

        private void label5_Click(object sender, EventArgs e)
        {
        }

        private void cpbLogo_Click(object sender, EventArgs e)
        {
        }

        private void rbtnTrash_Click(object sender, EventArgs e)
        {
        }
    }
}