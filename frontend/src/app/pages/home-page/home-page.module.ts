import { IonicModule } from '@ionic/angular';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HomePageComponent } from './home-page-component';

import { HomePageRoutingModule } from './home-page-routing.module';
import { ExploreContainerComponentModule } from 'src/app/components/explore-container/explore-container.module';

@NgModule({
  imports: [
    IonicModule,
    CommonModule,
    FormsModule,
    ExploreContainerComponentModule,
    HomePageRoutingModule
  ],
  declarations: [HomePageComponent]
})
export class HomePageModule {}
