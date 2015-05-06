package Data;

import java.util.ArrayList;

/**

 * @author Timo Bayer

 * @version 1.0

 */

public class Values {
	private ArrayList<String> values;
	
	public Values(){
		
	}
	
	public Values(ArrayList<String> values){
		this.values = values;
	}

	public ArrayList<String> getValues() {
		return values;
	}

	public void setValues(ArrayList<String> values) {
		this.values = values;
	}	
}