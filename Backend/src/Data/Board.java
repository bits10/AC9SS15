package Data;

/**

 * @author Timo Bayer

 * @version 1.0

 */ 

public class Board {
	private int id;
	private String ip;
	private String beschreibung;
	
	public Board(int id, String beschreibung, String ip){
		this.setId(id);
		this.setBeschreibung(beschreibung);
		this.setIp(ip);
	}
	
	public Board(){
		
	}

	public String getBeschreibung() {
		return beschreibung;
	}

	public void setBeschreibung(String beschreibung) {
		this.beschreibung = beschreibung;
	}

	public String getIp() {
		return ip;
	}

	public void setIp(String ip) {
		this.ip = ip;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}
	

}
