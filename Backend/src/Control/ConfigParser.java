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
	private String connection;
	private String username;
	private String pw;
	
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
				if(line.contains("DBConnection"))
				{
					setConnection(getValueFromLine(line));
				}
				else if(line.contains("DBUsername"))
				{
					setUsername(getValueFromLine(line));
				}
				else if(line.contains("DBPassword"))
				{
					setPw(getValueFromLine(line));
				}
				else
				{
					System.out.println("Unknown argument in config file -> see line "+lineNumber+":"+line);
				}			
			}
		}
		br.close();
	}

	public String getConnection() {
		return connection;
	}

	public void setConnection(String connection) {
		this.connection = connection;
	}

	public String getUsername() {
		return username;
	}

	public void setUsername(String username) {
		this.username = username;
	}

	public String getPw() {
		return pw;
	}

	public void setPw(String pw) {
		this.pw = pw;
	}
}
