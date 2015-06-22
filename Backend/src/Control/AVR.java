package Control;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.Socket;
import java.util.ArrayList;

import DB.DBConnector;
import Data.Ack;
import Data.Stat;
import Data.Values;

/**
 * 
 * @author Timo Bayer
 * 
 * @version 1.0
 */

public class AVR {
	private ConfigParser configParser = new ConfigParser();
	String ip = "192.168.0.90";
	String board1;
	String board2;
	String board3;
	int port = 50290;
	PrintWriter out = null;
	BufferedReader in = null;
	Socket socket;
	DBConnector db;
	
	public AVR() {
		try {
			configParser.parseConfig("/home/pi/project/Config.cfg");
			db = new DBConnector(configParser.getConnection(),
					configParser.getUsername(), configParser.getPw());
		} catch (IOException e) {
			e.printStackTrace();
		} catch (NullPointerException e) {

		}
	}

	public String setBoard(String ip) {
		this.ip = ip;
		return "ok";
	}

	public boolean initialBoard(String boardIP) {
		try {
			socket = new Socket(boardIP, port);
			in = new BufferedReader(new InputStreamReader(
					socket.getInputStream()));
			out = new PrintWriter(socket.getOutputStream());
			return true;
		} catch (IOException e) {
			return false;
		}
	}

	public void closeSocket() {
		try {
			socket.close();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}

	public Ack setPort(String boardIP, int port, int status) {
		Ack ack;
		if (initialBoard(boardIP)) {
			out.println("SETPORT " + port + "." + status);
			out.flush();
			try {
				String erg = in.readLine();
				if (erg.equals("ACK")) {
					ack = new Ack(true);
				} else {
					ack = new Ack(false);
				}
				closeSocket();
				return ack;
			} catch (IOException e) {
				
			}
		}
		return null;
	}

	public Ack setPorts(String boardIP, String values) {
		Ack ack;
		int stat1;
		int stat2;
		int stat3;
		int stat4;
		int stat5;
		int stat6;
		int stat7;
		int stat8;
		if (values.charAt(1) == '1') {
			stat1 = 1;
		} else {
			stat1 = 0;
		}
		if (values.charAt(2) == '1') {
			stat2 = 1;
		} else {
			stat2 = 0;
		}
		if (values.charAt(3) == '1') {
			stat3 = 1;
		} else {
			stat3 = 0;
		}
		if (values.charAt(4) == '1') {
			stat4 = 1;
		} else {
			stat4 = 0;
		}
		if (values.charAt(5) == '1') {
			stat5 = 1;
		} else {
			stat5 = 0;
		}
		if (values.charAt(6) == '1') {
			stat6 = 1;
		} else {
			stat6 = 0;
		}
		if (values.charAt(7) == '1') {
			stat7 = 1;
		} else {
			stat7 = 0;
		}
		if (values.charAt(8) == '1') {
			stat8 = 1;
		} else {
			stat8 = 0;
		}

		if (initialBoard(boardIP)) {
			out.println("SETPORT 1." + stat1);
			out.flush();
			out.println("SETPORT 2." + stat2);
			out.flush();
			try {
				Thread.sleep(10);
			} catch (InterruptedException e1) {
				e1.printStackTrace();
			}
			out.println("SETPORT 3." + stat3);
			out.flush();
			try {
				Thread.sleep(10);
			} catch (InterruptedException e1) {
				e1.printStackTrace();
			}
			out.println("SETPORT 4." + stat4);
			out.flush();
			try {
				Thread.sleep(10);
			} catch (InterruptedException e1) {
				e1.printStackTrace();
			}
			out.println("SETPORT 5." + stat5);
			out.flush();
			try {
				Thread.sleep(10);
			} catch (InterruptedException e1) {
				e1.printStackTrace();
			}
			out.println("SETPORT 6." + stat6);
			out.flush();
			try {
				Thread.sleep(10);
			} catch (InterruptedException e1) {
				e1.printStackTrace();
			}
			out.println("SETPORT 7." + stat7);
			out.flush();
			try {
				Thread.sleep(10);
			} catch (InterruptedException e1) {
				e1.printStackTrace();
			}
			out.println("SETPORT 8." + stat8);
			out.flush();
			try {
				String erg = in.readLine();
				if (erg.equals("ACK")) {
					ack = new Ack(true);
				} else {
					ack = new Ack(false);
				}
				closeSocket();
				return ack;
			} catch (IOException e) {
			
			}
		}
		return null;
	}

	public Stat getInPort(String boardIP) {
		Stat stat;
		ArrayList<Boolean> status = new ArrayList<Boolean>();
		for (int i = 1; i <= 4; i++) {
			if (initialBoard(boardIP)) {
				out.println("GETPORT " + i);
				out.flush();
				try {
					String erg = in.readLine();
					closeSocket();
					if ((erg.charAt(0)) == '1') {
						boolean stat1 = true;
						status.add(stat1);
					} else {
						boolean stat1 = false;
						status.add(stat1);
					}
				} catch (IOException e) {
					
				}
			} else {
				return null;
			}
		}
		stat = new Stat(status);
		return stat;
	}

	public Stat getOutStatus(String boardIP) {
		Stat stat;
		if (initialBoard(boardIP)) {
			out.println("GETSTATUS");
			out.flush();
			try {
				String erg = in.readLine();
				ArrayList<Boolean> status = new ArrayList<Boolean>();
				if (erg.length() > 7) {
					if ((erg.charAt(8)) == '1') {
						boolean stat1 = true;
						status.add(stat1);
					} else {
						boolean stat1 = false;
						status.add(stat1);
					}
					if ((erg.charAt(7)) == '1') {
						boolean stat2 = true;
						status.add(stat2);
					} else {
						boolean stat2 = false;
						status.add(stat2);
					}
					if ((erg.charAt(6)) == '1') {
						boolean stat3 = true;
						status.add(stat3);
					} else {
						boolean stat3 = false;
						status.add(stat3);
					}
					if ((erg.charAt(5)) == '1') {
						boolean stat4 = true;
						status.add(stat4);
					} else {
						boolean stat4 = false;
						status.add(stat4);
					}
					if ((erg.charAt(4)) == '1') {
						boolean stat5 = true;
						status.add(stat5);
					} else {
						boolean stat5 = false;
						status.add(stat5);
					}
					if ((erg.charAt(3)) == '1') {
						boolean stat6 = true;
						status.add(stat6);
					} else {
						boolean stat6 = false;
						status.add(stat6);
					}
					if ((erg.charAt(2)) == '1') {
						boolean stat7 = true;
						status.add(stat7);
					} else {
						boolean stat7 = false;
						status.add(stat7);
					}
					if ((erg.charAt(1)) == '1') {
						boolean stat8 = true;
						status.add(stat8);
					} else {
						boolean stat8 = false;
						status.add(stat8);
					}
				}
				stat = new Stat(status);
				closeSocket();
				return stat;
			} catch (IOException e) {
				
			}
		}
		return null;
	}

	public Values getAnalogInPort(String boardIP) {
		Values value;
		ArrayList<String> valuesAL = new ArrayList<String>();
		for (int i = 1; i <= 4; i++) {
			if (initialBoard(boardIP)) {
				out.println("GETADC " + i);
				out.flush();
				try {
					String erg = in.readLine();
					closeSocket();
					valuesAL.add(erg);
				} catch (IOException e) {
				
				}
			} else {
				return null;
			}
		}
		value = new Values(valuesAL);
		return value;
	}

	public String getIP(String boardIP) {
		if (initialBoard(boardIP)) {
			out.println("GETIP");
			out.flush();
			try {
				String erg = in.readLine();
				closeSocket();
				return erg;
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return null;
	}

	public String setIP(String boardIP, String ip) {
		if (initialBoard(boardIP)) {
			out.println("SETIP " + ip);
			out.flush();
			try {
				String erg = in.readLine();
				closeSocket();
				return erg;
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return null;
	}

	public String getMask(String boardIP) {
		if (initialBoard(boardIP)) {
			out.println("GETMASK");
			out.flush();
			try {
				String erg = in.readLine();
				closeSocket();
				return erg;
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return null;
	}

	public String setMask(String boardIP, String mask) {
		if (initialBoard(boardIP)) {
			out.println("SETMASK " + mask);
			out.flush();
			try {
				String erg = in.readLine();
				closeSocket();
				return erg;
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return null;
	}

	public String getGateway(String boardIP) {
		if (initialBoard(boardIP)) {
			out.println("GETGW");
			out.flush();
			try {
				String erg = in.readLine();
				closeSocket();
				return erg;
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return null;
	}

	public String setGateway(String boardIP, String gateway) {
		if (initialBoard(boardIP)) {
			out.println("SETGW " + gateway);
			out.flush();
			try {
				String erg = in.readLine();
				closeSocket();
				return erg;
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return null;
	}

	public String initLCD(String boardIP) {
		if (initialBoard(boardIP)) {
			out.println("INITLCD");
			out.flush();
			try {
				String erg = in.readLine();
				closeSocket();
				return erg;
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return null;
	}

	public String writeLCD(String boardIP, int line, String text) {
		if (initialBoard(boardIP)) {
			out.println("WRITELCD " + line + "." + text);
			out.flush();
			try {
				String erg = in.readLine();
				closeSocket();
				return erg;
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return null;
	}

	public String clearLCD(String boardIP) {
		if (initialBoard(boardIP)) {
			out.println("CLEARLCD");
			out.flush();
			try {
				String erg = in.readLine();
				closeSocket();
				return erg;
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return null;
	}

	public String getVersion(String boardIP) {
		if (initialBoard(boardIP)) {
			out.println("VERSION");
			out.flush();
			try {
				String erg = in.readLine();
				closeSocket();
				return erg;
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return null;
	}
}