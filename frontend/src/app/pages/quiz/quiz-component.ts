import { Component, Input, ViewChild } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { PickAnswerQuizComponent } from 'src/app/components/pick-answer-quiz/pick-answer-quiz.component';
import { QuizModel, QuizQuestion } from 'src/app/entities/quiz-question.model';
import { AssessmentsService } from 'src/app/services/assessments.service';

@Component({
  selector: 'quiz-component',
  templateUrl: 'quiz-component.html',
  styleUrls: ['quiz-component.scss']
})
export class QuizComponent {
  @ViewChild(PickAnswerQuizComponent) private pickAnswerQuizComponent!: PickAnswerQuizComponent;
  @Input() questions: QuizQuestion[] = [];
  loading = false;
  summaryData!: QuizModel;
  assessmentTypeId: any;
  assessmentId: any;
  assessmentName: any;
  showSummary: boolean = false;
  duration: any;

  constructor(
    private activatedRoute: ActivatedRoute,
    private assessmentsService: AssessmentsService,
  ) { }


  ngOnInit(): void {
    this.activatedRoute.queryParams.subscribe({
      next: (params) => {
        this.duration = params['duration'];
        this.assessmentName = params['assessmentName'];
        this.assessmentId = params['assessmentId'];
        this.assessmentTypeId = params['assessmentTypeId'];
        this.questions = JSON.parse(params['questions']);
      },
      error: (err) => console.error('Error reading query parameters:', err)
    });
    this.subscribeToQuizCompletion();
  }

  private subscribeToQuizCompletion() {
    if (this.pickAnswerQuizComponent) {
      this.pickAnswerQuizComponent.quizCompleted.subscribe(() => {
        const requestBody = {
          "assessmentTypeId": this.assessmentTypeId,
          "endTime": new Date().toISOString(),
          "userId": localStorage.getItem('username')
        }
        this.assessmentsService.completeAssessment(this.assessmentName, this.assessmentId, requestBody).subscribe(response => {
          this.summaryData = response.body.quiz;
          this.showSummary = true;
        },
          error => {
            this.loading = false;
            console.error(error);
          }
        );
      });
    } else {
      setTimeout(() => {
        this.subscribeToQuizCompletion();
      }, 100);
    }
  }

  onReplayQuiz() {
    this.showSummary = false;
  }

  onReturnToMenu() {
    console.log("onReturnToMenu");
  }
}
