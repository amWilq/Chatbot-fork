import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Assessment } from 'src/app/entities/assessments.model';
import { QuizQuestion, QuizModel } from 'src/app/entities/quiz-question.model';
import { AssessmentsService } from 'src/app/services/assessments.service';

@Component({
  selector: 'assessment-component',
  templateUrl: 'assessments-component.html',
  styleUrls: ['assessments-component.scss']
})

export class AssessmentsComponent implements OnInit {
  @Input() languageId!: any;
  @Input() categoryId!: any;

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
  duration: number = 3000; // 5 minutes

  constructor(
    private assessmentsService: AssessmentsService,
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
    this.assessments = res.body ?? [];
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
      "userDeviceId": localStorage.getItem('userId'),
      "categoryId": "2aeca473",
      "languageId": this.languageId,
      "difficulty": this.selectedDifficulty,
      "startTime": new Date().toISOString(),
      "duration": this.duration
    };
  }

  private handleStartAssessmentResponse(response: any): void {
    this.assessmentId = response.body.assessmentId;
    if (this.assessmentId && this.selectedCard && this.duration != null) {
      this.navigateWithParams();
    } else {
      console.error('Required data for navigation is missing');
    }
  }

  private navigateWithParams(): void {
    this.router.navigate(['/tabs/tab3'], {
      queryParams: {
        type: this.selectedCard.name,
        duration: this.duration.toString(),
        assessmentName: this.selectedCard.name,
        assessmentId: this.assessmentId,
        assessmentTypeId: this.selectedCard.assessmentTypeId.toString(),
      }
    }).then(() => {
      console.log('Navigation successful');
    }).catch(error => {
      console.error('Navigation error:', error);
    });
  }

  private logErrorAndStopLoading(error: any): void {
    this.loading = false;
    console.error(error);
  }

  private logError(message: string, error: any): void {
    console.error(message, error);
  }
}
