package Control;

import javax.ws.rs.*;
import javax.ws.rs.core.MediaType;
import Data.Ack;
import Data.Stat;
import Data.Values;


/**

 * @author Timo Bayer

 * @version 1.0

 */




@Path("rest")
public class EntryPoint {
	AVR avr = new AVR();
	

	@GET
	@Path("getStatus")
	@Produces(MediaType.APPLICATION_JSON)
	public Stat getStatus(@QueryParam("boardID") int boardID) {
		return avr.getOutStatus(boardID);
	}
	
	@GET
	@Path("setPorts")
	@Produces(MediaType.APPLICATION_JSON)
	public Ack setPort(@QueryParam("boardID") int boardID, @QueryParam("values") String values) {
		return avr.setPorts(boardID, values);
	}
	
	@GET
	@Path("getInPort")
	@Produces(MediaType.APPLICATION_JSON)
	public Stat getInPort(@QueryParam("boardID") int boardID) {
		return avr.getInPort(boardID);
	}
	
	@GET
	@Path("getAnalogInPort")
	@Produces(MediaType.APPLICATION_JSON)
	public Values getAnalogInPort(@QueryParam("boardID") int boardID) {
		return avr.getAnalogInPort(boardID);
	}
	
	@GET
	@Path("getIP")
	@Produces(MediaType.APPLICATION_JSON)
	public String getIP(@QueryParam("boardID") int boardID) {
		return avr.getIP(boardID);
	}
}