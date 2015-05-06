package Data;

public class Board {
	private int id;
	private String ip;
	private String beschreibung;
	
	public Board(int id, String beschreibung, String ip){
		this.id = id;
		this.beschreibung = beschreibung;
		this.ip = ip;
	}
	
	public int getId() {
		return id;
	}
	
	public String getIp() {
		return ip;
	}
	
	public String getBeschreibung() {
		return beschreibung;
	}
}
