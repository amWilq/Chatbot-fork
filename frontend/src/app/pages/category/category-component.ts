import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
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
  savedState: { [languageId: string]: { categoryId: string, item: Language } } = {};
  selectedFavCategory: any;

  constructor(
    private activatedRoute: ActivatedRoute,
    private languagesService: LanguagesService,
  ) { }

  ngOnInit(): void {
    this.subscribeToQueryParams();
    this.loadLocalStorageState();
  }

  ngDoCheck(){
    this.loadLocalStorageState();
  }

  private loadLocalStorageState(): void {
    const localStorageState = JSON.parse(localStorage.getItem('savedState') || '{}');
    this.savedState = localStorageState;
  }

  private subscribeToQueryParams(): void {
    this.activatedRoute.queryParams.subscribe({
      next: (params) => {
        this.selectedCategory = params['selectedCategory'];
        if ( params['selectedFavCategory']){
          this.selectedFavCategory = params['selectedFavCategory'];
          this.showAssessmentComponent = true;
        } else {
          this.loadLanguages();
        }
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

  private saveLocalStorageState(): void {
    localStorage.setItem('savedState', JSON.stringify(this.savedState));
  }

  toggleHeart(item: Language, categoryId: string): void {
    const languageId = item.languageId;
    if (this.savedState[languageId!] === undefined) {
      this.savedState[languageId!] = { categoryId, item };
    } else {
      delete this.savedState[languageId!];
    }
    this.saveLocalStorageState();
  }

}
