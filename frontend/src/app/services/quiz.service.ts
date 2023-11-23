import { Injectable } from '@angular/core';
import { QuizQuestion } from '../entities/quiz-question.model';
import { Observable, Subject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class QuizService {
  private questions: QuizQuestion[] = [];
  private quizsStatus: Subject<boolean> = new Subject<boolean>();
  private timeStatus: Subject<string> = new Subject<string>();

  setQuestions(questions: QuizQuestion[]) {
    this.questions = questions;
  }

  getQuestions(): QuizQuestion[] {
    return this.questions;
  }

  setQuizsStatus(status: boolean): void {
    this.quizsStatus.next(status);
  }

  getQuizsStatus(): Observable<boolean> {
    return this.quizsStatus.asObservable();
  }

  setTimeStatus(status: string): void {
    this.timeStatus.next(status);
  }

  getTimeStatus(): Observable<string> {
    return this.timeStatus.asObservable();
  }
}
