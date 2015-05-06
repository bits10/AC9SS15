package Data;

/**

 * @author Timo Bayer

 * @version 1.0

 */


public class Ack {
	private boolean stat;
	
	public Ack(){
		
	}
	
	public Ack(boolean stat){
		this.setStat(stat);
	}

	public boolean getStat() {
		return stat;
	}

	public void setStat(boolean stat) {
		this.stat = stat;
	}	
}
