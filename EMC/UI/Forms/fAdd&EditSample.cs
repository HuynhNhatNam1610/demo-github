using EMC.UI.Controls;
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
    public partial class fAdd_EditSample : Form
    {
        public fAdd_EditSample()
        {
            InitializeComponent();
            this.Resize += fAddEditSample_Resize;
        }

        private void fAdd_EditSample_Load(object sender, EventArgs e)
        {
            UIHelpers.LoadImage(cpbLogo, @"UI\Resources\images\logo.png", PictureBoxSizeMode.StretchImage);
            SetupDataGridView();
        }

        private void rbtnUploadImage_Click(object sender, EventArgs e)
        {
            using (OpenFileDialog ofd = new OpenFileDialog())
            {
                ofd.Filter = "Image Files|*.jpg;*.jpeg;*.png;*.bmp";
                if (ofd.ShowDialog() == DialogResult.OK)
                {
                    //pbSampleImage.Image = Image.FromFile(ofd.FileName);
                    //pbSampleImage.Tag = ofd.FileName; // lưu đường dẫn nếu cần
                }
            }
        }

        private void fAddEditSample_Resize(object sender, EventArgs e)
        {
            label5.Left = (panel2.Width - label5.Width) / 2;
            panel3.Left = (panel1.Width - panel3.Width) / 2;
        }

        //private void panel2_Resize(object sender, EventArgs e)
        //{
        //    label5.Left = (panel2.Width - label5.Width) / 2;
        //}

        //private void panel3_Resize(object sender, EventArgs e)
        //{
        //    panel3.Left = (panel3.Width - panel3.Width) / 2;
        //}


        //private void SetupDataGridView()
        //{
        //    DataGridView dgv = new DataGridView();
        //    dgv.Dock = DockStyle.Fill;
        //    dgv.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill;
        //    dgv.RowHeadersVisible = false;
        //    dgv.AllowUserToAddRows = false;

        //    // Thêm cột
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "#", Width = 40 });
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Chỉ tiêu" });
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Đơn vị" });
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Mã mẫu phụ" });
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Kết quả" });
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "TT" });
        //    dgv.Columns.Add(new DataGridViewCheckBoxColumn() { HeaderText = "Ẩn lần quan trắc" });

        //    // Cột combobox cho "Loại"
        //    var colLoai = new DataGridViewComboBoxColumn()
        //    {
        //        HeaderText = "Loại",
        //        Items = { "HT", "KQ", "Khác" }
        //    };
        //    dgv.Columns.Add(colLoai);

        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Người phân tích" });
        //    dgv.Columns.Add(new DataGridViewCheckBoxColumn() { HeaderText = "Thầu phụ" });

        //    // Thêm 1 dòng mẫu
        //    dgv.Rows.Add("1", "Từ trường", "A/m", "", "2,57", "", false, "HT", "", false);

        //    //DataGridViewButtonColumn colAdd = new DataGridViewButtonColumn();
        //    //colAdd.HeaderText = "";
        //    //colAdd.Text = "+";
        //    //colAdd.UseColumnTextForButtonValue = true;
        //    //colAdd.Width = 40;
        //    //dgv.Columns.Add(colAdd);
        //    //// Thêm vào panel18
        //    //panel18.Controls.Add(dgv);


        //}
        //private void SetupDataGridView()
        //{
        //    DataGridView dgv = new DataGridView();
        //    dgv.Dock = DockStyle.Fill;  // chiếm toàn bộ panel3
        //    dgv.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill;
        //    dgv.RowHeadersVisible = false;
        //    dgv.AllowUserToAddRows = false;

        //    // Thêm cột
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "#", Width = 40 });
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Chỉ tiêu" });
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Đơn vị" });
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Mã mẫu phụ" });
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Kết quả" });
        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "TT" });
        //    var colCheck = new DataGridViewCheckBoxColumn()
        //    {
        //        HeaderText = "Ẩn lần quan trắc"
        //    };
        //    // giữ nền trắng khi select
        //    colCheck.DefaultCellStyle.BackColor = Color.White;
        //    colCheck.DefaultCellStyle.SelectionBackColor = Color.White;
        //    dgv.Columns.Add(colCheck);

        //    var colLoai = new DataGridViewComboBoxColumn()
        //    {
        //        HeaderText = "Loại",
        //        Items = { "HT", "KQ", "Khác" }
        //    };
        //    dgv.Columns.Add(colLoai);

        //    dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Người phân tích" });
        //    dgv.Columns.Add(new DataGridViewCheckBoxColumn() { HeaderText = "Thầu phụ" });

        //    // Thêm 1 dòng mẫu
        //    dgv.Rows.Add("1", "Từ trường", "A/m", "", "2,57", "", false, "HT", "", false);

        //    // Gắn vào panel3
        //    panel18.Controls.Clear();      // xóa control cũ nếu muốn
        //    panel18.Controls.Add(dgv);     // thêm dgv vào panel3

        //    DataGridViewButtonColumn colAdd = new DataGridViewButtonColumn();
        //    colAdd.HeaderText = "";
        //    colAdd.Text = "+";
        //    colAdd.UseColumnTextForButtonValue = true;
        //    colAdd.Width = 40;
        //    dgv.Columns.Add(colAdd);
        //    // Thêm vào panel18
        //    panel18.Controls.Add(dgv);

        //    // Sự kiện CellPainting để giữ nền trắng cho checkbox
        //    dgv.CellPainting += (s, e) =>
        //    {
        //        if (e.ColumnIndex >= 0 && dgv.Columns[e.ColumnIndex] is DataGridViewCheckBoxColumn && e.RowIndex >= 0)
        //        {
        //            e.Handled = true;
        //            e.PaintBackground(e.ClipBounds, true); // nền trắng (theo DefaultCellStyle.BackColor)
        //            e.PaintContent(e.ClipBounds);          // vẽ checkbox
        //        }
        //    };
        //}
        private void SetupDataGridView()
        {
            DataGridView dgv = new DataGridView();
            dgv.Dock = DockStyle.Fill;
            dgv.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill;
            dgv.RowHeadersVisible = false;
            dgv.AllowUserToAddRows = false;

            // Thêm cột
            dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "#", Width = 40 });
            dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Chỉ tiêu" });
            dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Đơn vị" });
            dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Mã mẫu phụ" });
            dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "Kết quả" });
            dgv.Columns.Add(new DataGridViewTextBoxColumn() { HeaderText = "TT" });

            // Checkbox: Ẩn lần quan trắc
            var colCheck = new DataGridViewCheckBoxColumn()
            {
                HeaderText = "Ẩn lần quan trắc"
            };
            colCheck.DefaultCellStyle.BackColor = Color.White;
            colCheck.DefaultCellStyle.SelectionBackColor = Color.White;
            dgv.Columns.Add(colCheck);

            // Combobox: Loại
            var colLoai = new DataGridViewComboBoxColumn()
            {
                HeaderText = "Loại",
                Items = { "HT", "KQ", "Khác" }
            };
            dgv.Columns.Add(colLoai);

            // Combobox: Người phân tích
            var colNguoiPhanTich = new DataGridViewComboBoxColumn()
            {
                HeaderText = "Người phân tích",
                Items = { "Nguyễn Văn A", "Trần Thị B", "Lê Văn C" } // ví dụ, bạn có thể load từ DB
            };
            dgv.Columns.Add(colNguoiPhanTich);

            // Combobox: Thầu phụ
            var colThauPhu = new DataGridViewComboBoxColumn()
            {
                HeaderText = "Thầu phụ",
                Items = { "Có", "Không" }
            };
            dgv.Columns.Add(colThauPhu);

            // Thêm 1 dòng mẫu
            dgv.Rows.Add("1", "Từ trường", "A/m", "", "2,57", "", false, "HT", "Nguyễn Văn A", "Không");

            // Nút "+"
            DataGridViewButtonColumn colAdd = new DataGridViewButtonColumn();
            colAdd.HeaderText = "";
            colAdd.Text = "+";
            colAdd.UseColumnTextForButtonValue = true;
            colAdd.Width = 40;
            dgv.Columns.Add(colAdd);

            // Thêm vào panel
            panel18.Controls.Clear();
            panel18.Controls.Add(dgv);
        }



        private void panel2_Paint(object sender, PaintEventArgs e)
        {

        }
    }
}
