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
	@GET
	@Path("getStatus")
	@Produces(MediaType.APPLICATION_JSON)
	public Stat getStatus(@QueryParam("boardIP") String boardIP) {
		return App.getAvr().getOutStatus(boardIP);
	}
	
	@GET
	@Path("setPorts")
	@Produces(MediaType.APPLICATION_JSON)
	public Ack setPort(@QueryParam("boardIP") String boardIP, @QueryParam("values") String values) {
		return App.getAvr().setPorts(boardIP, values);
	}
	
	@GET
	@Path("getInPort")
	@Produces(MediaType.APPLICATION_JSON)
	public Stat getInPort(@QueryParam("boardIP") String boardIP) {
		return App.getAvr().getInPort(boardIP);
	}
	
	@GET
	@Path("getAnalogInPort")
	@Produces(MediaType.APPLICATION_JSON)
	public Values getAnalogInPort(@QueryParam("boardIP") String boardIP) {
		return App.getAvr().getAnalogInPort(boardIP);
	}
	
	@GET
	@Path("getIP")
	@Produces(MediaType.APPLICATION_JSON)
	public String getIP(@QueryParam("boardIP") String boardIP) {
		return App.getAvr().getIP(boardIP);
	}
	
	@GET
	@Path("initLCD")
	@Produces(MediaType.APPLICATION_JSON)
	public String initLCD(@QueryParam("boardIP") String boardIP) {
		return App.getAvr().initLCD(boardIP);
	}
	
	@GET
	@Path("clearLCD")
	@Produces(MediaType.APPLICATION_JSON)
	public String clearLCD(@QueryParam("boardIP") String boardIP) {
		return App.getAvr().initLCD(boardIP);
	}
	
	@GET
	@Path("writeLCD")
	@Produces(MediaType.APPLICATION_JSON)
	public String writeLCD(@QueryParam("boardIP") String boardIP, @QueryParam("line") int line, @QueryParam("text") String text) {
		return App.getAvr().writeLCD(boardIP, line, text);
	}

	@GET
	@Path("start")
	@Produces(MediaType.APPLICATION_JSON)
	public String start(@QueryParam("id") int id) {
		return App.getAvr().start(id);
	}
	
	@GET
	@Path("stop")
	@Produces(MediaType.APPLICATION_JSON)
	public String stop(@QueryParam("id") int id) {
		return App.getAvr().stop(id);
	}
}