package Control;

 

import org.eclipse.jetty.server.Server;
import org.eclipse.jetty.servlet.ServletContextHandler;
import org.eclipse.jetty.servlet.ServletHolder;

/**

 * @author Timo Bayer

 * @version 1.0

 */ 


public class App {
	private static AVR avr = new AVR();
	private static Anwendungen anw = new Anwendungen();
    public static void main(String[] args) throws Exception {
        ServletContextHandler context = new ServletContextHandler(ServletContextHandler.SESSIONS);
        context.setContextPath("/");
 
        Server jettyServer = new Server(8088);
        jettyServer.setHandler(context);
 
        ServletHolder jerseyServlet = context.addServlet(
             org.glassfish.jersey.servlet.ServletContainer.class, "/*");
        jerseyServlet.setInitOrder(0);
 
        jerseyServlet.setInitParameter(
           "jersey.config.server.provider.classnames",
           EntryPoint.class.getCanonicalName());
        

        try {
            jettyServer.start();
            jettyServer.join();
        } finally {
            jettyServer.destroy();
        }
    }
	public static AVR getAvr() {
		return avr;
	}
	public static void setAvr(AVR avr) {
		App.avr = avr;
	}
	public static Anwendungen getAnw() {
		return anw;
	}
	public static void setAn(Anwendungen anw) {
		App.anw = anw;
	}
}