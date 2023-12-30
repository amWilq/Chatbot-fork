import { QuizService } from 'src/app/services/quiz.service';

import { Component, EventEmitter, Input, Output, ViewChild } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription, catchError, take, throwError } from 'rxjs';
import { PickAnswerQuizComponent } from 'src/app/components/pick-answer-quiz/pick-answer-quiz.component';
import { QuizModel } from 'src/app/entities/quiz-question.model';
import { AssessmentsService } from 'src/app/services/assessments.service';
@Component({
  selector: 'quiz-component',
  templateUrl: 'quiz-component.html',
  styleUrls: ['quiz-component.scss']
})
export class QuizComponent {
  @ViewChild(PickAnswerQuizComponent) private pickAnswerQuizComponent!: PickAnswerQuizComponent;
  @Output() botCommentAdded: EventEmitter<string> = new EventEmitter<string>();
  @Input() questions: any;
  loading = false;
  loadingCompleteQuiz = false;
  summaryData: QuizModel | null = null;
  assessmentTypeId: any;
  assessmentId: any;
  assessmentName: any;
  showSummary: boolean = false;
  duration: any;
  type: string = '';
  private queryParamsSubscription!: Subscription;
  private quizCompletedSubscription!: Subscription;
  private completeAssessmentSubscription!: Subscription;
  quizCompleted: boolean = false;

  constructor(
    public router: Router,
    public activatedRoute: ActivatedRoute,
    private assessmentsService: AssessmentsService,
    private quizService: QuizService
  ) { }

  ngOnInit(): void {
    this.loading = true;
    this.subscribeToQueryParams();
    this.loadQuizStatus();
  }

  private subscribeToQueryParams() {
    this.loading = true;
    this.queryParamsSubscription = this.activatedRoute.queryParams.pipe(
      catchError(err => {
        console.error('Error fetching query params:', err);
        this.loading = false;
        return throwError(err);
      })
    ).subscribe(params => {
      this.assessmentId = params['assessmentId'];
      this.assessmentName = params['assessmentName'];
      this.assessmentTypeId = params['assessmentTypeId'];
      this.duration = params['duration'];
      this.type = params['type'];
      this.loading = false;
      this.loadQuizStatus();
    });
  }


  ngOnDestroy(): void {
    if (this.queryParamsSubscription) {
      this.queryParamsSubscription.unsubscribe();
    }
    if (this.quizCompletedSubscription) {
      this.quizCompletedSubscription.unsubscribe();
    }
    if (this.completeAssessmentSubscription) {
      this.completeAssessmentSubscription.unsubscribe();
    }
  }

  async completeQuiz(): Promise<void> {
    this.loadingCompleteQuiz = true;
    await this.assessmentsService.completeAssessment(this.assessmentName, this.assessmentId).subscribe({
      next: (response) => {
        this.loadingCompleteQuiz = false;
        this.showSummary = true;
        this.summaryData = response.body;
      },
      error: (error) => console.log(error)
    });
  }

  private async loadQuizStatus() {
    this.quizService.getQuizsStatus().pipe(take(1)).subscribe(status => {
      if (status && !this.quizCompleted) {
        this.completeQuiz();
        this.quizCompleted = true;
      } else {
        console.log('Quiz is in progress');
      }
    });
  }

  onReplayQuiz() {
    this.showSummary = false;
    window.location.reload();
  }

  onReturnToMenu() {
    this.questions = [];
    this.router.navigate(['/tabs/tab1']).then(() => {
      setTimeout(() => {
        window.location.reload();
      });
    });
    this.showSummary = false;
  }
}
