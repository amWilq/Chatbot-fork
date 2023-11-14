import { Component } from '@angular/core';
import { Assessment } from 'src/app/entities/assessments.model';
import { AssessmentsService } from 'src/app/services/assessments.service';

@Component({
  selector: 'assessment-component',
  templateUrl: 'assessments-component.html',
  styleUrls: ['assessments-component.scss']
})
export class AssessmentsComponent {
  assessments: Assessment[] = [];
  selectedCard! : any;
  loading: boolean = false;
  selectedDifficulty: string = '';

  constructor(
    private assessmentsService: AssessmentsService,

  ) { }

  ngOnInit(): void {
    this.loadAssessments();
  }

  private loadAssessments(): void {
    this.assessmentsService.getAllAssessments().subscribe({
      next: (res: any) => {
        this.assessments = res.body.items ?? [];
        if (this.assessments.length > 0) {
          this.selectedCard = this.assessments[0];
        }
      },
      error: (err) => console.error('Error fetching assessments:', err)
    });
  }

  onDifficultyChange(event: any) {
    this.selectedDifficulty = event.detail.value;
    console.log('Selected difficulty:', this.selectedDifficulty);
  }

  getIconForDifficulty(difficulty: string): string {
    switch (difficulty) {
      case 'beginner':
        return 'star-outline';
      case 'intermediate':
        return 'star-half';
      case 'advanced':
        return 'star';
      default:
        return 'help';
    }
  }

  onCardClick(format: any): void {
    this.selectedCard = format;
    console.error('Selected card:', this.selectedCard);
  }

  onBackButtonClicked() {
    window.location.reload();
  }

  onStartQuizClicked() {
    this.loading = true;
    console.log('Start quiz clicked!', this.selectedDifficulty, this.selectedCard);
  }

}
