using System;
using System.Collections.Generic;
using System.Drawing.Drawing2D;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace EMC.UI.Helpers
{
    public static class UIHelpers
    {
        public static void LoadImage(PictureBox pictureBox, string relativePath, PictureBoxSizeMode sizeMode)
        {
            string fullPath = Path.Combine(Application.StartupPath, relativePath);

            if (File.Exists(fullPath))
            {
                pictureBox.Image = Image.FromFile(fullPath);
                pictureBox.SizeMode = sizeMode;
            }
            else
            {
                pictureBox.Image = null; // hoặc gán ảnh mặc định
            }
        }

        public static void RoundPictureBox(PictureBox pic, int radius)
        {
            if (pic.Width > 0 && pic.Height > 0)
            {
                GraphicsPath path = new GraphicsPath();
                int w = pic.Width;
                int h = pic.Height;

                path.StartFigure();
                // Top-left
                path.AddArc(0, 0, radius, radius, 180, 90);
                // Top-right
                path.AddArc(w - radius, 0, radius, radius, 270, 90);
                // Bottom-right
                path.AddArc(w - radius, h - radius, radius, radius, 0, 90);
                // Bottom-left
                path.AddArc(0, h - radius, radius, radius, 90, 90);
                path.CloseFigure();

                pic.Region = new Region(path);
            }
        }

        public static void RoundPictureBoxLeftCorners(PictureBox pic, int radius)
        {
            if (pic.Width > 0 && pic.Height > 0)
            {
                GraphicsPath path = new GraphicsPath();
                int w = pic.Width;
                int h = pic.Height;

                path.StartFigure();
                // Top-left
                path.AddArc(0, 0, radius, radius, 180, 90);
                // Top edge → Top-right (vuông)
                path.AddLine(radius, 0, w, 0);
                // Right edge → xuống dưới (vuông)
                path.AddLine(w, 0, w, h);
                // Bottom edge → Bottom-left (bo tròn)
                path.AddArc(0, h - radius, radius, radius, 90, 90);
                path.CloseFigure();

                pic.Region = new Region(path);
            }
        }
    }
}
