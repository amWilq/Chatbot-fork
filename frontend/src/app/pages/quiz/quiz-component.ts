import { QuizService } from './../../services/quiz.service';
import { Component, Input, ViewChild } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription } from 'rxjs';
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
  @Input() questions: any;
  loading = false;
  summaryData!: QuizModel;
  assessmentTypeId: any;
  assessmentId: any;
  assessmentName: any;
  showSummary: boolean = false;
  duration: any;
  type: any;
  private queryParamsSubscription!: Subscription;
  private quizCompletedSubscription!: Subscription;
  private completeAssessmentSubscription!: Subscription;
  quizCompleted: boolean = false;

  constructor(
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private assessmentsService: AssessmentsService,
    private quizService: QuizService,
  ) { }


  ngOnInit(): void {
    this.queryParamsSubscription = this.activatedRoute.queryParams.subscribe({
      next: (params) => {
        this.type = params['type'];
        this.duration = params['duration'];
        this.assessmentName = params['assessmentName'];
        this.assessmentId = params['assessmentId'];
        this.assessmentTypeId = params['assessmentTypeId'];
        if (this.type === 'code-snippet') {
          this.questions = JSON.parse(params['questions'])
        } else if (this.type === 'quiz') {
          this.questions = JSON.parse(params['questions'])
        }
      }
    });
    this.subscribeToQuizCompletion();
    this.loadQuizStatus();
  }

  private subscribeToQuizCompletion() {
    if (this.pickAnswerQuizComponent && !this.quizCompleted) {
      this.quizCompletedSubscription = this.pickAnswerQuizComponent.quizCompleted.subscribe(() => {
        const requestBody = {
          "assessmentTypeId": this.assessmentTypeId,
          "endTime": new Date().toISOString(),
          "userId": localStorage.getItem('userId')
        }
        this.completeAssessmentSubscription = this.assessmentsService.completeAssessment(this.assessmentName, this.assessmentId, requestBody).subscribe(response => {
          this.quizService.setQuizsStatus(true);
          this.quizService.setTimeStatus('');
          this.summaryData = response.body.quiz;
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


  private loadQuizStatus() {
    this.quizService.getQuizsStatus().subscribe((e) => {
      this.quizCompleted = e;
      this.showSummary = e;
    });
  }

  onReplayQuiz() {
    this.showSummary = false;
    window.location.reload();
  }

  onReturnToMenu() {
    this.questions = [];
    // Przejdź na stronę główną
    this.router.navigate(['/tabs/tab1']).then(() => {
      setTimeout(() => {
        window.location.reload();
      });
    });
    // Zresetuj stan komponentu
    this.showSummary = false;
  }
}
