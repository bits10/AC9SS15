import java.io.IOException;

import com.sampullara.cli.Args;


public class Main {

	public static void main(String[] args) {
		try {
			HTMLHeaderCompiler h = new HTMLHeaderCompiler();
			Args.parse(h, args);
			h.compile();
		} catch (IllegalArgumentException e) {
			System.out.println("Wrong Arguments");
			System.out.println("Usage: hhc.jar -in <INPUT FOLDER> -out <OUTPUT FILE>");
			
			System.out.println("");
			System.out.println("-in, -input\tThe input directory [required]");
			System.out.println("-out, -output\tThe output file [required]");
			System.out.println("-v, -verbose\tVerbose Mode");
			System.out.println("-n, -newline\tKeeps \\n, \\r and \\t in the output");		      
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
}
