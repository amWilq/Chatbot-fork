import { Component, ViewChild, ChangeDetectorRef, Input } from '@angular/core';
import { Router } from '@angular/router';
import { PickAnswerQuizComponent } from 'src/app/components/pick-answer-quiz/pick-answer-quiz.component';
import { Assessment } from 'src/app/entities/assessments.model';
import { QuizQuestion, QuizModel } from 'src/app/entities/quiz-question.model';
import { AssessmentsService } from 'src/app/services/assessments.service';
enum AssessmentType {
  Quiz = 'quiz',
  FreeText = 'free-text',
  CodeSnippet = 'code-snippet',
  MultipleChoice = 'multiple-choice',
}

@Component({
  selector: 'assessment-component',
  templateUrl: 'assessments-component.html',
  styleUrls: ['assessments-component.scss']
})
export class AssessmentsComponent {
  @Input() languageId!: any;
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
  duration = 2;

  constructor(
    private assessmentsService: AssessmentsService,
    private cdr: ChangeDetectorRef,
    private router: Router

  ) { }

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
      "duration": this.duration,
      "startTime": new Date().toISOString(),
      "userId": localStorage.getItem('userId')
    };
  }

  private handleStartAssessmentResponse(response: any): void {
    this.assessmentId = response.body.assessmentId;
    const requestBody2 = this.createSecondAssessmentRequest(response.body.assessmentId);

    // Add the new payload to the request body
    const body = { "requestType": "generateOutput" };
    Object.assign(requestBody2, body);

    this.assessmentsService.startAssessmentGenerate(this.selectedCard.name, this.assessmentId, requestBody2).subscribe({
      next: (response) => this.handleQuizQuestionsResponse(response),
      error: (error) => this.logErrorAndStopLoading(error)
    });
  }

  private handleQuizQuestionsResponse(response: any) {
    console.log(response)

    switch (this.selectedCard.name) {
      case 'quiz':
        this.questions = response.body.data.question; // For quiz
        break;
      case 'free-text':
        this.questions = response.body.data.aiResponse.message; // For free-text
        break;
      case 'code-snippet':
        this.questions = response.body.data.snippet; // For code-snippet
        break;
      case 'multiple-choice':
        this.questions = response.body.data.multipleChoice; // For multiple-choice
        break;
      default:
        console.error('Unknown question type:', this.selectedCard.name);
        return;
    }
    this.loading = false;
    this.cdr.detectChanges();
    if (this.questions) {
      this.router.navigate(['/tabs/tab3'], {
        queryParams: {
          type: this.selectedCard.name,
          duration: this.duration,
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
      "userId": localStorage.getItem('userId'),
      "requestType": this.selectedCard.name,
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

  private logError(message: string, error: any): void {
    console.error(message, error);
  }
}
