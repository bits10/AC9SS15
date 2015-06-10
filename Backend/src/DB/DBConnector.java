package DB;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;

import Data.Anwendung;
import Data.Board;

public class DBConnector {
	private PreparedStatement prepared;
	private Connection con;

	public DBConnector(String connection, String username, String pw) {
		try {
			con = DriverManager.getConnection(connection, username, pw);
			String insert = "select * from AVR.Boards";
			prepared = con.prepareStatement(insert);
		} catch (SQLException e) {
			e.printStackTrace();
		}
	}

	public ArrayList<Board> getBoards() {
		try {
			ArrayList<Board> boards = new ArrayList<Board>();
			Statement statement = con.createStatement();
			String select = "select * from AVR.Boards";
			ResultSet results = statement.executeQuery(select);
			while (results.next()) {
				int id = results.getInt("id");
				String ip = results.getString("IP");
				String beschreibung = results.getString("beschreibung");
				Board board = new Board(id, beschreibung, ip);
				boards.add(board);
			}
			results.close();
			return boards;
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return null;
	}

	public Board getBoard(int zielID) {
		try {
			Board board = new Board();
			Statement statement = con.createStatement();
			String select = "select * from AVR.Boards where id="+zielID;
			ResultSet results = statement.executeQuery(select);
			while (results.next()) {
				int id = results.getInt("id");
				String ip = results.getString("IP");
				String beschreibung = results.getString("beschreibung");
				board.setId(id);
				board.setBeschreibung(beschreibung);
				board.setIp(ip);
			}
			results.close();
			return board;
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return null;
	}
	
	public Anwendung getAnwendung(int id) {
		try {
			Anwendung anw = new Anwendung();
			Statement statement = con.createStatement();
			String select = "select * from AVR.Anwendungen where id="+id;
			ResultSet results = statement.executeQuery(select);
			while (results.next()) {
				String skript = results.getString("skript");
				String name = results.getString("name");
				int boardID = results.getInt("boardID");
				anw.setName(name);
				anw.setSkript(skript);
				anw.setBoardID(boardID);
				anw.setId(id);
			}
			results.close();
			return anw;
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return null;
	}
	
}
