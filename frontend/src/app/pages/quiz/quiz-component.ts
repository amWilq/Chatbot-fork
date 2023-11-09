import { Component } from '@angular/core';
import { QuizQuestion } from 'src/app/entities/quiz-question.model';

@Component({
  selector: 'quiz-component',
  templateUrl: 'quiz-component.html',
  styleUrls: ['quiz-component.scss']
})
export class QuizComponent {

  selectedAnswers: string[] = [];
  showSummary: boolean = false;
  quizQuestions: QuizQuestion[] = [
    {
      question: "What is the capital of France?",
      answers: ["Paris", "London", "Berlin", "Rome"],
      correctAnswer: "Paris"
    },
    {
      question: "Which element's chemical symbol is O?",
      answers: ["Oxygen", "Gold", "Silver", "Iron"],
      correctAnswer: "Oxygen"
    },
    {
      question: "What is the largest planet in our Solar System?",
      answers: ["Jupiter", "Mars", "Earth", "Venus"],
      correctAnswer: "Jupiter"
    }
  ];

  handleAnswerSelected(event: { selected: string; correct: boolean }): void {
    this.selectedAnswers.push(event.selected);
    if (this.selectedAnswers.length === this.quizQuestions.length) {
      this.showSummary = true;
    }
  }

  onReplayQuiz() {
    this.selectedAnswers = [];
    this.showSummary = false;
  }

  onReturnToMenu() {

  }
}
