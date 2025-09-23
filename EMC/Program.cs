using EMC.UI.Forms;

namespace EMC
{
    internal static class Program
    {
        /// <summary>
        ///  The main entry point for the application.
        /// </summary>
        [STAThread]
        static void Main()
        {
            // To customize application configuration such as set high DPI settings or default font,
            // see https://aka.ms/applicationconfiguration.
            ApplicationConfiguration.Initialize();
            //fReview f2 = new fReview();
            fLogin f1 = new fLogin();
            fBusiness f3 = new fBusiness();
            fReview f2 = new fReview();
            Form1 f4 = new Form1();
            PhongKinhDoanh f5 = new PhongKinhDoanh();

            //f1.Show();
            f5.Show();
            //f4.Show();
            //form1.Show();
            Application.Run();
        }
    }
}