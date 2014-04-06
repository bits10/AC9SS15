import java.io.BufferedInputStream;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.FilenameFilter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import com.sampullara.cli.Argument;

public class HTMLHeaderCompiler implements FilenameFilter {

	@Argument(value="input", alias="in", required=true)
	private String rootDirectory;
	@Argument(value="output", alias="out", required=true)
	private String outputFilePath;
	private String fileEnd = "%END";
	private long totalWebpageSize = 0;

	public void compile() throws IOException {
		
		System.out.println("HTML Header Compiler 0.1");
		System.out.println("===========");

		List<File> webpageFiles = this.listFilesInRoot();

		File outputFile = new File(outputFilePath);
		outputFile.getParentFile().mkdirs();
		BufferedWriter outputWriter = new BufferedWriter(new FileWriter(outputFile));

		this.writeFileInit(outputWriter);
		outputWriter.newLine();

		for(int i=0; i<webpageFiles.size(); i++) {
			this.writeCharArrayDump(webpageFiles.get(i), i, outputWriter);
			outputWriter.newLine();

		}

		this.writeFileIndex(webpageFiles, outputWriter);
		outputWriter.newLine();
		this.writeFileEnd(outputWriter);

		outputWriter.flush();
		outputWriter.close();

		System.out.println("===========");
		System.out.println("Compiled \"" + this.rootDirectory + "\" to \"" + outputFile.getAbsolutePath() + "\".") ;
		System.out.println("Total webpage size: " + this.totalWebpageSize + " Bytes / " + (this.totalWebpageSize / 1000) + " Kilobyte");

	}

	private List<File> listFilesInRoot() {
		List<File> list = new ArrayList<File>();

		File f = new File(this.rootDirectory);
		this.listFiles(f, list);

		return list;
	}

	private List<File> listFiles(File f, List<File> list) {

		if(f.isDirectory()) {
			for(File item : f.listFiles(this))
				this.listFiles(item, list);

		} else {
			list.add(f);

		}

		return list;
	}

	private void writeFileInit(BufferedWriter outputWriter) throws IOException {
		outputWriter.write("#ifndef _WEBPAGE_H");
		outputWriter.newLine();
		outputWriter.write("#define _WEBPAGE_H");
		outputWriter.newLine();

	}

	private void writeCharArrayDump(File f, int id, BufferedWriter outputWriter) throws IOException {

		char buffer[] = new char[(int) (f.length() + this.fileEnd.length())];
		int index = 0;
		int readValue = 0;
		BufferedInputStream input = new BufferedInputStream(new FileInputStream(f));

		System.out.println("Processing \"" + this.getRelativePathFromRoot(f) + "\"...");
		
		//Einlesen
		while( (readValue = input.read()) >= 0) {
			buffer[index++] = (char) readValue;

			if(buffer[index-1] != readValue)
				System.out.println(readValue + " -> " + buffer[index-1]);
		}

		for(int i=buffer.length-this.fileEnd.length(); i<buffer.length; i++)
			buffer[i] = (char) this.fileEnd.charAt(i-buffer.length+this.fileEnd.length());

		//Optimieren
		if(f.getName().endsWith(".html") || f.getName().endsWith(".htm") 
				|| f.getName().endsWith(".js") || f.getName().endsWith(".css")) {
			String s = new String(buffer);
			buffer = null;

			s = s.replaceAll("\n", "");
			s = s.replaceAll("\r", "");
			s = s.replaceAll("\t", "");
			s = s.replaceAll("\\s{2,}", " ");

			if(f.getName().endsWith(".html") || f.getName().endsWith(".htm")) {
				s = s.replaceAll("<!--(.*?)-->", "");

			} else if(f.getName().endsWith(".js")) {
				s = s.replaceAll("/\\*(.*?)\\*/", "");
				s = s.replaceAll("//(.*?)$", "");

			} else if(f.getName().endsWith(".css")) {
				s = s.replaceAll("/\\*(.*?)\\*/", "");

			}

			buffer = new char[s.length()];
			s.getChars(0, s.length(), buffer, 0); 
		}

		//Ausgeben
		outputWriter.write("//" + this.createFieldNameForId(id) + ": " 
				+ this.getRelativePathFromRoot(f)
				+ " (" + buffer.length + " Bytes, " + f.length() + " Bytes in file)");
		outputWriter.newLine();
		outputWriter.write("const PROGMEM char " + this.createFieldNameForId(id) + "[] = {");

		for(int i=0; i<buffer.length; i++) {
			if(i%16 == 0) {
				outputWriter.newLine();
				outputWriter.write(this.tab());
			}

			outputWriter.write(this.getHexString(buffer[i]));

			if(i < buffer.length-1)
				outputWriter.write(", ");
		}

		this.totalWebpageSize += buffer.length;

		input.close();
		outputWriter.write("}");
		outputWriter.newLine();

	}

	private String getHexString(int i) {
		String v = Integer.toHexString(i).toUpperCase();

		switch (v.length()) {
		case 0: return "0x00";
		case 1: return "0x0" + v;
		default: return "0x" + v;
		}

	}

	private void writeFileIndex(List<File> files, BufferedWriter outputWriter) throws IOException {
		outputWriter.write("//file index (total webpage size: " + this.totalWebpageSize + " Bytes / " + (this.totalWebpageSize / 1000) + " Kilobyte)");
		outputWriter.newLine();
		outputWriter.write("WEBPAGE_ITEM WEBPAGE_TABLE[] = {");
		outputWriter.newLine();

		for(int i=0; i<files.size(); i++) {
			outputWriter.write(this.tab() + "{\"/" + this.getRelativePathFromRoot(files.get(i))
					+ "\", " + this.createFieldNameForId(i) + "}");
			outputWriter.newLine();
		}

		outputWriter.write("}");
		outputWriter.newLine();


	}

	private void writeFileEnd(BufferedWriter outputWriter) throws IOException {
		outputWriter.write("#endif //_WEBPAGE_H");

	}

	private String createFieldNameForId(int id) {
		return "item_" + id;
	}

	private String tab() {
		return "      ";
	}

	private String getRelativePathFromRoot(File f) throws IOException {
		if(f.getAbsolutePath().startsWith(this.rootDirectory)) {
			return f.getAbsolutePath().substring(this.rootDirectory.length() + 1);

		} else {
			throw new IOException(f.getAbsolutePath() + " is not a child of root!");

		}

	}

	@Override
	public boolean accept(File dir, String name) {
		return !name.startsWith(".");
	}
}
