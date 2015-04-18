package Data;

import java.util.ArrayList;

/**

 * @author Timo Bayer

 * @version 1.0

 */

public class Stat {
	ArrayList<Boolean> status;
	
	public Stat(){
		
	}
	
	public Stat(ArrayList<Boolean> status){
		this.status = status;
	}
	
	public ArrayList<Boolean> getStatus(){
		return status;
	}
	
	public void setStatus(ArrayList<Boolean> status){
		this.status = status;
	}
}
