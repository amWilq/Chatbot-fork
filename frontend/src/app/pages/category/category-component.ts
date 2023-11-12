import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AlertController } from '@ionic/angular';
import { Assessment } from 'src/app/entities/assessments.model';
import { Language } from 'src/app/entities/languages.model';
import { AssessmentsService } from 'src/app/services/assessments.service';
import { LanguagesService } from 'src/app/services/languages.service';

@Component({
  selector: 'app-category',
  templateUrl: './category-component.html',
  styleUrls: ['./category-component.scss']
})
export class CategoryComponent implements OnInit {
  selectedCategory: string | null = null;
  selectedItemId: Language | null = null;
  filteredItems: Language[] = [];
  originalItems: Language[] = [];
  showAssessmentComponent: boolean = false;

  constructor(
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private languagesService: LanguagesService,
    private assessmentsService: AssessmentsService,
    private alertController: AlertController

  ) { }

  ngOnInit(): void {
    this.subscribeToQueryParams();
  }

  private subscribeToQueryParams(): void {
    this.activatedRoute.queryParams.subscribe({
      next: (params) => {
        this.selectedCategory = params['selectedCategory'];
        this.loadLanguages();
      },
      error: (err) => console.error('Error reading query parameters:', err)
    });
  }

  private loadLanguages(): void {
    if (!this.selectedCategory) return;

    this.languagesService.getAllLanguagesForCategory(this.selectedCategory).subscribe({
      next: (res: any) => {
        this.originalItems = res.body.items ?? [];
        this.filteredItems = [...this.originalItems];
      },
      error: (err) => console.error('Error fetching languages:', err)
    });
  }

  onItemSelect(item: Language): void {
    this.selectedItemId = item;
  }

  onSearch(event: Event): void {
    const searchText = (event.target as HTMLInputElement).value.toLowerCase();
    this.filteredItems = searchText ? this.filterItems(searchText) : [...this.originalItems];
  }

  private filterItems(searchText: string): Language[] {
    return this.originalItems.filter((item) => item.name?.toLowerCase().includes(searchText));
  }

  onStartQuiz() {
    this.showAssessmentComponent = true;
    // this.router.navigate(['tabs/tab3'], {
    //   queryParams: {
    //     selectedCategory: this.selectedCategory,
    //     disabled: true
    //   }
    // });
  }

}
