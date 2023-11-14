import { Injectable } from "@angular/core";
import { SERVER_API_URL } from "../app.constants";
import { HttpClient, HttpResponse } from "@angular/common/http";
import { Observable } from "rxjs";
import { Assessment } from "../entities/assessments.model";

export type EntityArrayResponseType = HttpResponse<Assessment[]>;

@Injectable({
  providedIn: 'root'
})
export class AssessmentsService {

  private ASSESSMENTS_LIST_URL = SERVER_API_URL + '/assessments/types';

  //  new url = /assessments/{assessmentTypeName}/start

  constructor(
    private http: HttpClient
  ){}

  getAllAssessments(): Observable<EntityArrayResponseType> {
    return this.http.get<Assessment[]>(`${this.ASSESSMENTS_LIST_URL}`, { observe: 'response' });
  }

  startAssessment(assessmentTypeName: string, body: any): Observable<HttpResponse<any>> {
    const startUrl = `${SERVER_API_URL}/assessments/${assessmentTypeName}/start`;
    return this.http.post<any>(startUrl, body, { observe: 'response' });
  }

  startAssessmentGenerate(assessmentTypeName: string,assessmentId: string, body: any): Observable<HttpResponse<any>> {
    const startUrl = `${SERVER_API_URL}/assessments/${assessmentTypeName}/${assessmentId}`;
    return this.http.post<any>(startUrl, body, { observe: 'response' });
  }

}
