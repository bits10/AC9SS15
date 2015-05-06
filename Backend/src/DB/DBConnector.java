package DB;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;

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

	public boolean insertSeries(int id, String series, int volumes) {
		try {
			prepared.setInt(1, id);
			prepared.setString(2, series);
			prepared.setInt(3, volumes);
			return 1 == prepared.executeUpdate();
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return false;
	}
}
