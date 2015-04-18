package Control;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;

/**

 * @author Timo Bayer

 * @version 1.0

 */
public class ConfigParser 
{
	private String board1;
	private String board2;
	private String board3;
	
	private String getValueFromLine(String line)
	{
		if(line.contains("="))
		{
			return new String(line.substring(line.indexOf("=")+1, line.length()));
		}
		return null;
	}
	
	public void parseConfig(String pathToConfig) throws IOException
	{
		BufferedReader br = new BufferedReader(new FileReader(pathToConfig));
		String line;
		int lineNumber=0;
		while ((line = br.readLine()) != null) 
		{
			lineNumber++;			
			if(!line.startsWith("#") && line.length()!=0)
			{
				if(line.contains("Board1"))
				{
					setBoard1(getValueFromLine(line));
				}
				else if(line.contains("Board2"))
				{
					setBoard2(getValueFromLine(line));
				}
				else if(line.contains("Board3"))
				{
					setBoard3(getValueFromLine(line));
				}
				else
				{
					System.out.println("Unknown argument in config file -> see line "+lineNumber+":"+line);
				}			
			}
		}
		br.close();
	}

	public String getBoard1() {
		return board1;
	}

	public void setBoard1(String board1) {
		this.board1 = board1;
	}

	public String getBoard2() {
		return board2;
	}

	public void setBoard2(String board2) {
		this.board2 = board2;
	}

	public String getBoard3() {
		return board3;
	}

	public void setBoard3(String board3) {
		this.board3 = board3;
	}
}
