import { Injectable } from "@angular/core";
import { HttpClient, HttpResponse } from "@angular/common/http";
import { SERVER_API_URL, WW_SERVER_API_URL } from "../app.constants";
import { WebSocketSubject, webSocket } from "rxjs/webSocket";
import { Observable } from "rxjs";
import { Assessment } from "../entities/assessments.model";
export type EntityArrayResponseType = HttpResponse<Assessment[]>;

@Injectable({
  providedIn: 'root'
})
export class AssessmentsService {

  private ASSESSMENTS_LIST_URL = SERVER_API_URL + '/assessments/types';
  private userAnswerSocket$: WebSocketSubject<any> | null = null;

  constructor(
    private http: HttpClient
  ) { }

  initWebSocket(assessmentTypeName: string, assessmentId: string,) {
    const userAnswerUrl = `${WW_SERVER_API_URL}/assessments/?assessmentTypeName=${assessmentTypeName}&assessmentId=${assessmentId}`;
    this.userAnswerSocket$ = webSocket(userAnswerUrl);
  }

  getAllAssessments(): Observable<EntityArrayResponseType> {
    return this.http.get<Assessment[]>(`${this.ASSESSMENTS_LIST_URL}`, { observe: 'response' });
  }

  startAssessment(assessmentTypeName: string, body: any): Observable<HttpResponse<any>> {
    const startUrl = `${SERVER_API_URL}/assessments/${assessmentTypeName}/start`;
    return this.http.post<any>(startUrl, body, { observe: 'response' });
  }

  startAssessmentGenerate(assessmentTypeName: string, assessmentId: string, body: any): Observable<any> {
    const startUrl = `${WW_SERVER_API_URL}/assessments/?assessmentTypeName=${assessmentTypeName}&assessmentId=${assessmentId}`;
    const startSocket$ = webSocket(startUrl);
    return startSocket$.asObservable();
  }

  completeAssessment(assessmentTypeName: string, assessmentId: string): Observable<any> {
    const bodyComplete = {
      "userDeviceId": localStorage.getItem('userId'),
      "endTime": new Date().toISOString()
    }
    const startUrl = `${SERVER_API_URL}/assessments/${assessmentTypeName}/${assessmentId}/complete`;
    return this.http.post<any>(startUrl, bodyComplete, { observe: 'response' });
  }

  getGenerateOutput(assessmentTypeName: string, assessmentId: string): Observable<any> {
    return new Observable<any>((observer) => {
      if (this.userAnswerSocket$) {
        const body = {
          "assessmentTypeName": assessmentTypeName,
          "assessmentId": assessmentId,
          "requestType": "generateOutput"
        };

        this.userAnswerSocket$.next(body);
        this.userAnswerSocket$.subscribe(
          (response) => {
            observer.next(response);
            observer.complete();
          },
          (error) => {
            observer.error(error);
          }
        );
      } else {
        observer.error("WebSocket is not initialized");
      }
    });
  }



  sendUserAnswer(assessmentTypeName: string, assessmentId: string, answer: any, takenTime: number): Observable<any> {
    return new Observable<any>((observer) => {
      if (this.userAnswerSocket$) {
        const bodyUserAnswer = {
          "assessmentTypeName": assessmentTypeName,
          "assessmentId": assessmentId,
          "requestType": "userInput",
          "data": {
            "answer": answer,
            "takenTime": takenTime
          }
        };
        this.userAnswerSocket$.next(bodyUserAnswer);
        this.userAnswerSocket$.subscribe(
          (response) => {
            observer.next(response);
            observer.complete();
          },
          (error) => {
            observer.error(error);
          }
        );
      } else {
        observer.error("WebSocket is not initialized");
      }
    });
  }


}
