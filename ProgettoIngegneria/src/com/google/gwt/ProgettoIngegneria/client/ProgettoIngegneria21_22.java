package com.google.gwt.ProgettoIngegneria.client;

import com.google.gwt.core.client.EntryPoint;
import com.google.gwt.core.client.GWT;
import com.google.gwt.user.client.Window;
import com.google.gwt.user.client.rpc.AsyncCallback;

public class ProgettoIngegneria21_22 implements EntryPoint {

	private final static GreetingServiceAsync gs = GWT.create(GreetingService.class);
	
	protected static ControllerPages controller = new ControllerPages();
	
	public void onModuleLoad() {

		
	gs.registraAmministratore(new AsyncCallback<Void>() {

			@Override
			public void onSuccess(Void result) {
			}
			@Override
			public void onFailure(Throwable caught) {
				Window.alert("admin non registrato: " + caught.getMessage());
			}
		});
		ControllerPages.showThisPage("HomePage", "");
	}
}