import { Injectable } from '@angular/core';
import { QuizQuestion } from '../entities/quiz-question.model';

@Injectable({
  providedIn: 'root'
})
export class QuizService {
  private questions: QuizQuestion[] = [];

  setQuestions(questions: QuizQuestion[]) {
    this.questions = questions;
  }

  getQuestions(): QuizQuestion[] {
    return this.questions;
  }
}
