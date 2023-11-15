import { HttpResponse } from '@angular/common/http';
import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { Router } from '@angular/router';
import { Category } from 'src/app/entities/category.model';
import { CategoryService } from 'src/app/services/category.service';

@Component({
  selector: 'home-page-component',
  templateUrl: 'home-page-component.html',
  styleUrls: ['home-page-component.scss']
})
export class HomePageComponent implements OnInit {
  @Output() newItemEvent = new EventEmitter<string>();

  isClicked = false;
  isCardVisible = true;
  selectedFavCard: any[] = [];
  sampleFavData: Category[] = [];
  selectedFavCardIndex: number | null = null;
  selectedCategoryCardIndex: number | null = null;
  categorys: Category[] = [];

  constructor(private router: Router, private categoryService: CategoryService) { }

  ngOnInit() {
    this.loadAllCategories();
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
    this.sampleFavData = Object.values(localStorageState).map((entry: any) => ({
      item: entry.item,
      categoryId: entry.categoryId
    }));
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
    this.selectedFavCard = card;
    console.error('onFavoriteCardClick', this.selectedFavCard);
  }

  onDeleteCard(card: Category) {
    const index = this.sampleFavData.indexOf(card);
    if (index >= 0) {
      this.sampleFavData.splice(index, 1);
      localStorage.setItem('savedState', JSON.stringify(this.sampleFavData));
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
    this.router.navigate(['tabs/tab2'], {
      queryParams: {
        // selectedFavCategory: (JSON.stringify(this.selectedFavCard)),
      }
    });
  }
}
