import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { TabsPage } from './tabs.page';

const routes: Routes = [
  {
    path: 'tabs',
    component: TabsPage,
    children: [
      {
        path: 'tab1',
        loadChildren: () => import('../home-page/home-page.module').then(m => m.HomePageModule)
      },

      {
        path: 'tab2',
        loadChildren: () => import('../category/category.module').then(m => m.CategoryModule)
      },
      {
        path: 'tab3',
        loadChildren: () => import('../quiz/quiz.module').then(m => m.QuizModule)
      },
      {
        path: '',
        redirectTo: '/tabs/tab1',
        pathMatch: 'full'
      }
    ]
  },
  {
    path: '',
    redirectTo: '/tabs/tab1',
    pathMatch: 'full'
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
})
export class TabsPageRoutingModule {}
