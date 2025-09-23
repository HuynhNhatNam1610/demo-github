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
    public partial class fBusiness : Form
    {
        public fBusiness()
        {
            InitializeComponent();
        }

        private void fBusiness_Load(object sender, EventArgs e)
        {
            UIHelpers.LoadImage(cpbLogo, @"UI\Resources\images\logo.png", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(cpbAvatar, @"UI\Resources\uploads\anhthe.jpg", PictureBoxSizeMode.StretchImage);

            // Xóa cột cũ (nếu có) rồi thêm mới
            dataGridView1.Columns.Clear();

            // Thêm cột
            dataGridView1.Columns.Add("MaHD", "Mã Hợp Đồng");
            dataGridView1.Columns.Add("TenKH", "Tên Khách Hàng");
            dataGridView1.Columns.Add("NgayKy", "Ngày Ký");
            dataGridView1.Columns.Add("GhiChu", "Ghi chú"); // cột thêm vào để dàn trải

            // Set cho các cột tự động chiếm hết chiều rộng
            dataGridView1.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill;

            // Thêm vài dòng dữ liệu mẫu
            dataGridView1.Rows.Add("HD001", "Nguyễn Văn A", DateTime.Now.AddDays(-10).ToShortDateString(), "Khách hàng VIP");
            dataGridView1.Rows.Add("HD002", "Trần Thị B", DateTime.Now.AddDays(-5).ToShortDateString(), "Gia hạn lần 1");
            dataGridView1.Rows.Add("HD003", "Lê Văn C", DateTime.Now.ToShortDateString(), "Mới ký");

            // Căn giữa header
            dataGridView1.ColumnHeadersDefaultCellStyle.Alignment = DataGridViewContentAlignment.MiddleCenter;
            dataGridView1.EnableHeadersVisualStyles = false;

            dataGridView1.DefaultCellStyle.SelectionBackColor = Color.Gray;
            dataGridView1.DefaultCellStyle.SelectionForeColor = Color.White;
            dataGridView1.SelectionMode = DataGridViewSelectionMode.FullRowSelect;
        }



        private void label1_Click(object sender, EventArgs e)
        {

        }

        private void pbLogo_Click(object sender, EventArgs e)
        {

        }

        private void pbSearch_Click(object sender, EventArgs e)
        {

        }
    }
}
