import { Injectable } from "@angular/core";
import { Observable, Observer } from "rxjs";
import { SERVER_ADDRES } from "../app.constants";
import * as SockJS from 'sockjs-client';
import * as Stomp from 'webstomp-client';

@Injectable({
  providedIn: 'root'
  })
export class WebSocketService {
  stompClient: any;
  connection: Promise<any> | undefined;
  connectedPromise: any;
  listener: Observable<any>;
  listenerObserver: Observer<any> | undefined;
  reconnectAttempt = 0;
  socket: any;

  constructor(){
    this.listener = this.createListener();
  }

  connect() {
    this.connection = this.createConnection();
    this.socket = new SockJS(`//${SERVER_ADDRES}/websocket/zka-locations?access`);
    this.stompClient = Stomp.over(this.socket);
    this.stompClient.debug = () => { };

    const headers = {};
    this.stompClient.connect(
      headers,
      () => {
        this.reconnectAttempt = 0;
        this.connectedPromise('success');
      }
    );
    this.socket.onclose = (closeEvent: CloseEvent) => {
      const timeout = 5000 + (5000 * Math.min(10, this.reconnectAttempt));
      this.reconnectAttempt++;
      setTimeout(() => {
        this.reconnect();
      }, timeout);
    };
  }

  reconnect() {
    this.unsubscribe();
    this.connect();
    this.subscribe();
  }

  disconnect() {
    if (this.stompClient !== null) {
      if (this.connectedPromise !== null) {
        this.connection!.then(() => {
          this.stompClient.disconnect();
          this.stompClient = null;
        });
      } else {
        this.stompClient.disconnect();
        this.stompClient = null;
      }
    }
  }

  receive() {
    return this.listener;
  }

  subscribe() {
    this.connection!.then(() => {
      this.stompClient.subscribe('/topic/zka-locations', (data: { body: string; }) => {
        this.listenerObserver!.next(JSON.parse(data.body));
      });
    });
  }

  unsubscribe() {
    if (this.stompClient?.connected) {
      this.stompClient.unsubscribe();
    }
  }

  sendData(data: any, destination: string) {
    this.stompClient.send(destination, {}, JSON.stringify(data));
  }

  private createListener = (): Observable<any> =>
    new Observable(observer => this.listenerObserver = observer);

  private createConnection = async (): Promise<any> =>
    await new Promise((resolve, reject) => (this.connectedPromise = resolve));

}
