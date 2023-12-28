import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { QuizModel } from 'src/app/entities/quiz-question.model';

@Component({
  selector: 'app-quiz-summary',
  templateUrl: './quiz-summary.component.html',
  styleUrls: ['./quiz-summary.component.scss']
})
export class QuizSummaryComponent implements OnInit {
  @Input() quizResponse: QuizModel[] = [] ;
  data!: any;

  @Output() replayQuiz: EventEmitter<void> = new EventEmitter<void>();
  @Output() returnToMenu = new EventEmitter<void>();

  ngOnInit(): void {
    this.data = {
      "assessmentId": "70470b41",
      "assessmentState": "ASSESSMENT_COMPLETE_SUCCESS",
      "userDeviceId": "6948DF80-14BD-4E04-8842-7668D9C001F5",
      "CategoryId": "d48e46e9",
      "LanguageId": "8906e84c",
      "difficultyAtStart": "beginner",
      "difficultyAtEnd": "beginner",
      "startTime": "2023-11-11T20:20:00Z",
      "endTime": "2023-11-11T22:20:00Z",
      "feedback": "Well done!",
      "assessmentDetails": {
        "assessmentTypeId": "a34a4a1a",
        "assessmentTypeName": "quiz",
        "answeredQuestions": 2,
        "correctAnswers": 2,
        "duration": 5,
        "questions": [
          {
            "content": "What does the echo statement do in PHP?",
            "answers": [
              "It returns a value from a function.",
              "It outputs one or more strings to the screen.",
              "It sets a variable.",
              "It terminates the execution of the script."
            ],
            "correctAnswer": "It outputs one or more strings to the screen.",
            "explanation": "The echo statement outputs one or more strings.",
            "yourAnswer": "It outputs one or more strings to the screen.",
            "isCorrect": true,
            "timeToAnswer": 10
          },
          {
            "content": "What does the print statement do in PHP?",
            "answers": [
              "It returns a value from a function.",
              "It outputs one or more strings to the screen.",
              "It sets a variable.",
              "It terminates the execution of the script."
            ],
            "correctAnswer": "It outputs one or more strings to the screen.",
            "explanation": "The print statement outputs one or more strings.",
            "yourAnswer": "It outputs one or more strings to the screen.",
            "isCorrect": false,
            "timeToAnswer": 10
          }
        ]
      }
    }
  }

  onReplayQuiz(): void {
    this.replayQuiz.emit();
  }

  onReturnToMenu(): void {
    this.returnToMenu.emit();
  }
}
