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
  private comment: Subject<string> = new Subject<string>();

  setQuestions(questions: QuizQuestion[]) {
    this.questions = questions;
  }

  getQuestions(): QuizQuestion[] {
    return this.questions;
  }

  setQuizsStatus(status: boolean): void {
    this.quizsStatus.next(status);
  }

  setTimeStatus(status: string): void {
    this.timeStatus.next(status);
  }

  getTimeStatus(): Observable<string> {
    return this.timeStatus.asObservable();
  }

  setBotComment(comment: string): void {
    this.comment.next(comment);
  }

  getBotComment(): Observable<string> {
    return this.comment.asObservable();
  }

  setQuizInProgress(): void {
    this.quizsStatus.next(false);
  }

  setQuizCompleted(): void {
    this.quizsStatus.next(true);
  }

  getQuizsStatus(): Observable<boolean> {
    return this.quizsStatus.asObservable();
  }
}
