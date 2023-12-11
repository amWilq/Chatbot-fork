import { HttpResponse } from '@angular/common/http';
import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { Router } from '@angular/router';
import { Category } from 'src/app/entities/category.model';
import { Language } from 'src/app/entities/languages.model';
import { CategoryService } from 'src/app/services/category.service';

interface SelectedFavCard {
  item: {
    languageId: number;
    name: string;
    categoriesId: number[];
  };
  categoryId: string;
}

@Component({
  selector: 'home-page-component',
  templateUrl: 'home-page-component.html',
  styleUrls: ['home-page-component.scss']
})
export class HomePageComponent implements OnInit {
  @Output() newItemEvent = new EventEmitter<string>();

  isClicked = false;
  isCardVisible = true;
  selectedFavCard: SelectedFavCard[] = [];
  sampleFavData: Category[] = [];
  selectedFavCardIndex: number | null = null;
  selectedCategoryCardIndex: number | null = null;
  categorys: Category[] = [];
  username: string | null = null;
  usernameInput: string = '';
  showAssessmentComponent: boolean = false;
  selectedFavCardLanguageId: number | null = null;
  savedState: { [languageId: string]: { categoryId: string, item: Language } } = {};

  constructor(private router: Router, private categoryService: CategoryService) { }

  ngOnInit() {
    this.loadAllCategories();
    this.loadFavDataFromLocalStorage();
  }

  ngAfterViewChecked(){
    this.loadFavDataFromLocalStorage();
  }

  private loadAllCategories() {
    this.categoryService.getAllCategories().subscribe(
      (res: HttpResponse<any>) => {
        this.categorys = res.body.items ?? [];
      },
      error => {
        console.error('Error fetching categories:', error);
      }
    );
  }

  private loadFavDataFromLocalStorage() {
    const localStorageState = JSON.parse(localStorage.getItem('savedState') || '{}');
    this.savedState = localStorageState;
  }

  private updateSelectedCardIndex(selectedIndex: number | null, currentIndex: number, isFavData: boolean) {
    if (selectedIndex === currentIndex) {
      if (isFavData) {
        this.selectedFavCardIndex = null;
      } else {
        this.selectedCategoryCardIndex = null;
      }
    } else {
      if (isFavData) {
        this.selectedFavCardIndex = currentIndex;
      } else {
        this.selectedCategoryCardIndex = currentIndex;
      }
    }
  }

  onFavoriteCardClick(card: any, index: number, isFavData: boolean) {
    this.selectedFavCard = [];
    const selectedIndex = isFavData ? this.selectedFavCardIndex : this.selectedCategoryCardIndex;
    this.updateSelectedCardIndex(selectedIndex, index, isFavData);
    if (isFavData) {
      this.selectedFavCard.push(card);
    }
    this.selectedFavCardLanguageId = this.selectedFavCard[0].item.languageId
  }

  onDeleteCard(card: any) {
    const languageId = card.value.item.languageId;
    if (languageId) {
      delete this.savedState[languageId];
      localStorage.setItem('savedState', JSON.stringify(this.savedState));
    }
  }

  onToggleCardVisibility() {
    this.isCardVisible = !this.isCardVisible;
  }

  onHandleArrowIconClick(navigateTo: string, card: Category) {
    this.router.navigate(['/tabs/' + navigateTo], {
      queryParams: {
        disabled: true,
        selectedCategory: card.categoryId
      }
    });
  }

  goToCategoryView() {
    if (this.selectedFavCardLanguageId ) {
      this.router.navigate(['/tabs/tab2'], {
        queryParams: {
          selectedFavCategory: this.selectedFavCardLanguageId
        }
      });
    }
  }
}
