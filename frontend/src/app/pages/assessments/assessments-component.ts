import { Component, ViewChild, ChangeDetectorRef, Input } from '@angular/core';
import { Router } from '@angular/router';
import { PickAnswerQuizComponent } from 'src/app/components/pick-answer-quiz/pick-answer-quiz.component';
import { Assessment } from 'src/app/entities/assessments.model';
import { QuizQuestion, QuizModel } from 'src/app/entities/quiz-question.model';
import { AssessmentsService } from 'src/app/services/assessments.service';

@Component({
  selector: 'assessment-component',
  templateUrl: 'assessments-component.html',
  styleUrls: ['assessments-component.scss']
})
export class AssessmentsComponent {
  @ViewChild(PickAnswerQuizComponent) private pickAnswerQuizComponent!: PickAnswerQuizComponent;
  @Input() languageId!: any ;
  assessments: Assessment[] = [];
  selectedCard!: any;
  loading = false;
  selectedDifficulty = '';
  showPickAnswerQuiz = false;
  questions: QuizQuestion[] = [];
  isCompleted = false;
  assessmentId = '';
  showSummary: boolean = false;
  summaryData!: QuizModel;

  constructor(
    private assessmentsService: AssessmentsService,
    private cdr: ChangeDetectorRef,
    private router: Router

  ) {}

  ngOnInit(): void {
    this.loadAssessments();
  }

  private loadAssessments(): void {
    this.assessmentsService.getAllAssessments().subscribe({
      next: (res) => this.handleAssessmentResponse(res),
      error: (err) => this.logError('Error fetching assessments:', err)
    });
  }

  private handleAssessmentResponse(res: any): void {
    this.assessments = res.body.items ?? [];
    this.selectedCard = this.assessments.length > 0 ? this.assessments[0] : null;
  }

  onDifficultyChange(event: any): void {
    this.selectedDifficulty = event.detail.value;
  }

  getIconForDifficulty(difficulty: string): string {
    switch (difficulty) {
      case 'beginner': return 'star-outline';
      case 'intermediate': return 'star-half';
      case 'advanced': return 'star';
      default: return 'help';
    }
  }

  onCardClick(format: Assessment): void {
    this.selectedCard = format;
    console.log(this.selectedCard);
  }

  onBackButtonClicked(): void {
    window.location.reload();
  }

  onStartQuizClicked(): void {
    this.loading = true;
    const requestBody = this.createStartAssessmentRequest();
    this.assessmentsService.startAssessment(this.selectedCard.name, requestBody).subscribe({
      next: (response) => this.handleStartAssessmentResponse(response),
      error: (error) => this.logErrorAndStopLoading(error)
    });
  }

  private createStartAssessmentRequest(): any {
    return {
      "assessmentTypeId": this.selectedCard.assessmentTypeId.toString(),
      "duration": 5,
      "startTime": new Date().toISOString(),
      "userId": "1"
    };
  }

  private handleStartAssessmentResponse(response: any): void {
    this.assessmentId = response.body.assessmentId;
    const requestBody2 = this.createSecondAssessmentRequest(response.body.assessmentId);
    console.log(this.selectedCard.name, this.assessmentId, requestBody2)
    this.assessmentsService.startAssessmentGenerate(this.selectedCard.name, this.assessmentId, requestBody2).subscribe({
      next: (response) => this.handleQuizQuestionsResponse(response),
      error: (error) => this.logErrorAndStopLoading(error)
    });
  }

  private handleQuizQuestionsResponse(response: any): void {
    this.questions = response.body.quiz.question;
    console.log(this.questions);
    // this.showPickAnswerQuiz = true;
    this.loading = false;
    this.cdr.detectChanges();
    this.subscribeToQuizCompletion();
    console.log(this.questions);
    if (this.questions) {
      this.router.navigate(['/tabs/tab3'], {
        queryParams: {
          assessmentName: this.selectedCard.name,
          assessmentId: this.assessmentId,
          assessmentTypeId: this.selectedCard.assessmentTypeId.toString(),
          questions: JSON.stringify(this.questions)
        }
      });
    }
  }

  private createSecondAssessmentRequest(assessmentId: string): any {
    return {
      "assessmentTypeId": this.selectedCard.assessmentTypeId.toString(),
      "assessmentId": assessmentId,
      "userId": "1",
      "requestType": "question",
      "languageId": this.languageId,
      "data": {
        "format": this.selectedCard.format,
        "difficulty": this.selectedDifficulty,
      }
    };
  }

  private logErrorAndStopLoading(error: any): void {
    this.loading = false;
    console.error(error);
  }

  private subscribeToQuizCompletion() {
    const requestBody = {
        "assessmentTypeId": this.selectedCard.assessmentTypeId.toString(),
        "endTime": new Date().toISOString(),
        "userId": "1"
    }

    if (this.pickAnswerQuizComponent) {
      this.pickAnswerQuizComponent.quizCompleted.subscribe(() => {
        this.assessmentsService.completeAssessment(this.selectedCard.name,this.assessmentId, requestBody).subscribe(response => {
          this.summaryData = response.body.quiz;
          console.warn( response.body.quiz);
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

  private logError(message: string, error: any): void {
    console.error(message, error);
  }
}
