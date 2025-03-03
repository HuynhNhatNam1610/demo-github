package ReportPart2;
import javax.swing.JFrame;
import javax.swing.JMenuBar;
public class CreateFrame {
    private JFrame frame;
    private JMenuBar menuBar;
    public CreateFrame(String title){
        frame = new JFrame(title);
        menuBar = new CustomMenuBar().creatMenuBar();
    }
    public void showFrame(){
        frame.setJMenuBar(menuBar);
        frame.setSize(400,300);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setVisible(true);
    }
}
