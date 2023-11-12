import { Injectable } from "@angular/core";
import { SERVER_API_URL } from "../app.constants";
import { HttpClient, HttpResponse } from "@angular/common/http";
import { Observable } from "rxjs";
import { Category } from "../entities/category.model";
import { Language } from "../entities/languages.model";

export type EntityArrayResponseType = HttpResponse<Language[]>;

@Injectable({
  providedIn: 'root'
})
export class LanguagesService {

  private LANGUAGE_LIST_URL = SERVER_API_URL + '/languages';

  constructor(
    private http: HttpClient
  ){}

  getAllCategories(): Observable<EntityArrayResponseType> {
    return this.http.get<Language[]>(`${this.LANGUAGE_LIST_URL}`, { observe: 'response' });
  }
  getAllLanguagesForCategory(categoriesId: string): Observable<EntityArrayResponseType> {
    return this.http.get<Language[]>(`${this.LANGUAGE_LIST_URL}/category/${categoriesId}`, { observe: 'response' });
  }

}
