package Control;

import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.HashMap;

import DB.DBConnector;
import Data.Anwendung;
import Data.Board;

/**

 * @author Timo Bayer

 * @version 1.0

 */ 

public class Anwendungen {
	HashMap map = new HashMap();
	Process q;
	DBConnector db;
	AVR avr = new AVR();
	
	public String start(int id) {
		Runtime r = Runtime.getRuntime();
		Anwendung anw = db.getAnwendung(id);
		Board board = db.getBoard(anw.getBoardID());
		PrintWriter writer = null;
		try {
			String fileName = "/home/pi/" + anw.getName() + ".c";
			writer = new PrintWriter(new BufferedWriter(
					new FileWriter(fileName)));
			writer.print(anw.getSkript());
			writer.close();
			Process p = r.exec("gcc " + fileName
					+ " /home/pi/avrSL.so -o /home/pi/" + anw.getName());
			Thread.sleep(2000);
			q = r.exec("/home/pi/" + anw.getName() + " " + board.getIp());
			map.put(id, q);
		} catch (IOException | InterruptedException e) {
			e.printStackTrace();
		}
		return anw.getSkript();

	}

	public String stop(int id) {
		Process p = (Process) map.get(id);
		p.destroy();
		Anwendung anw = db.getAnwendung(id);
		Board board = db.getBoard(anw.getBoardID());
		App.getAvr().setPorts(board.getIp(), "S00000000");
		App.getAvr().clearLCD(board.getIp());
		return "ok";
	}
}
