import java.io.IOException;

import com.sampullara.cli.Args;


public class Main {

	public static void main(String[] args) {
		try {
			HTMLHeaderCompiler h = new HTMLHeaderCompiler();
			Args.parse(h, args);
			h.compile();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
}
