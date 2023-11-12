import { Injectable } from "@angular/core";
import { SERVER_API_URL } from "../app.constants";
import { HttpClient, HttpResponse } from "@angular/common/http";
import { Observable } from "rxjs";
import { Category } from "../entities/category.model";

export type EntityArrayResponseType = HttpResponse<Category[]>;

@Injectable({
  providedIn: 'root'
  })
export class CategoryService {

  private CATEGORY_LIST_URL = SERVER_API_URL + '/categories';

  constructor(
    private http: HttpClient
  ){}

  getAllCategories(): Observable<EntityArrayResponseType> {
    return this.http.get<Category[]>(`${this.CATEGORY_LIST_URL}`, { observe: 'response' });

  }

}
