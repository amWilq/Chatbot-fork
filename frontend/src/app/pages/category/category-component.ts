import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Language } from 'src/app/entities/languages.model';
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
    private languagesService: LanguagesService,
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
  }

}
