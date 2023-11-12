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
          this.selectedCard = this.assessments[0]; // Select the first assessment
        }
      },
      error: (err) => console.error('Error fetching assessments:', err)
    });
  }
  onDifficultyChange(event: any) {
    console.log('Selected difficulty:', event.detail.value);
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
    console.log('Clicked format:', format);
  }




}
